<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\MailManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager, MailManager $mailManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            $mailManager->registrationEmail($user);
            $this->addFlash('success', 'Account registered successfully, We have sent you an email for verification. Verify your account before login');


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/accountVerification/{id}/{username}", name="account_verification")
     */
    public function accountVerification(Request $request, $username,$id, EntityManagerInterface $entityManager, MailManager $mailManager): Response
    {
       $user = $entityManager->getRepository(User::class)->findOneBy(['id'=>$id,'username'=>$username]);
       if ($user){
           $user->setPermissiongranted(true);
           $entityManager->persist($user);
           $entityManager->flush();
           $this->addFlash('success', 'Account verified successfully, Please Login');
           return $this->redirectToRoute('app_login');
       }
        $this->addFlash('failed', 'Account not found');
        return $this->redirectToRoute('app_register');
    }
}
