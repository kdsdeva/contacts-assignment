<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Service\MailManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/forgotpassword", name="forgotpassword")
     */
    public function forgotPasswordEmail(Request $request, EntityManagerInterface $entityManager, MailManager $mailManager): Response
    {
        $email = $request->query->get('email');
        if($email) {
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $mailManager->forgotPasswordEmail($user);
                $this->addFlash("success","Reset password link sent to your email");
                return $this->redirectToRoute('forgotpassword');
            }else{
                $this->addFlash("failed","User not found, enter valid Email");
                return $this->redirectToRoute('forgotpassword');
            }
        }
        return $this->render('user/forgot_password.html.twig');
    }

    /**
     * @Route("/setnewpassword/{id}/{username}" , name ="setnewpassword")
     */
    public function setNewPassword(Request $request, $id,$username, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
    {

        $userPersist = $entityManager->getRepository(User::class)->findOneBy(['id' => $id,'username'=>$username]);
        if($userPersist){
            $user = new User();
            $form = $this->createForm(ForgotPasswordType::class, $user);
            $locale = $request->getLocale();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $userPersist->setPassword($userPasswordEncoder->encodePassword($userPersist, $form->get('plainPassword')->getData()));
                $entityManager->persist($userPersist);
                $entityManager->flush();
                $this->addFlash('success', 'Password Updated Successfully');
                return $this->redirectToRoute('app_login');
            }
            return $this->render('user/set_password.html.twig', [
                'locale' => $locale,
                'forgotpassword' => $form->createView(),
            ]);
        }
        $this->addFlash('failed', 'Link or user is not valid');
        return $this->redirectToRoute('app_register');

    }
}