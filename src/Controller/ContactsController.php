<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Entity\User;
use App\Form\ContactsType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contacts")
 * @IsGranted("ROLE_USER")
 */
class ContactsController extends AbstractController
{
    /**
     * @Route("/contactslist", name="contactslist")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);
        $contacts = $entityManager->getRepository(Contacts::class)->findBy(['user'=>$user]);
        return $this->render('contacts/contacts.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/createcontact", name="create_contact")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contacts();
        $user = $entityManager->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUser($user);
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash('contactSuccess', "Contact created successfully");
            return $this->redirectToRoute('contactslist');
        }

        return $this->render('contacts/create_contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="view_contact")
     */
    public function view(EntityManagerInterface $entityManager,$id): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);
        $contact = $entityManager->getRepository(Contacts::class)->findOneBy(['id'=>$id,'user'=>$user]);
        if(!$contact){
            throw new NotFoundHttpException('You cannot access this contact');
        }
        return $this->render('contacts/view_contact.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_contact")
     */
    public function edit(Request $request,$id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['id'=>$this->getUser()]);
        $contact = $entityManager->getRepository(Contacts::class)->findOneBy(['id'=>$id,'user'=>$user]);
        if(!$contact){
            throw new NotFoundHttpException('You cannot access this contact');
        }
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->addFlash('contactSuccess', "Contact updated successfully");
            return $this->redirectToRoute('contactslist');
        }

        return $this->render('contacts/update_contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete_contact")
     */
    public function delete(Request $request,$id,EntityManagerInterface $entityManager): Response
    {
        $contact = $entityManager->getRepository(Contacts::class)->findOneBy(['id'=>$id]);
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();
            $this->addFlash('contactSuccess', "Contact deleted successfully");
        }
        return $this->redirectToRoute('contactslist');
    }
}
