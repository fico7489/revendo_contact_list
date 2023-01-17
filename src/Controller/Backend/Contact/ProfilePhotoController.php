<?php

namespace App\Controller\Backend\Contact;

use App\Entity\Contact;
use App\Form\ContactForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePhotoController extends AbstractController
{
    #[Route('/contacts/create', name: 'backend.contacts.create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            if ($contact instanceof Contact) {
                $contact->setFavorite(false);
                $em->persist($contact);
                $em->flush();
            }

            $this->addFlash('success', 'Contact successfully created!');

            return $this->redirectToRoute('backend.contacts.index');
        }

        return $this->render('backend/contacts/create.html.twig', [
            'form' => $form,
        ]);
    }
}
