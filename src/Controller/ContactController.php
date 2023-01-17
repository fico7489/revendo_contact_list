<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contacts', name: 'contacts.index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $contacts = $em->getRepository(Contact::class)->findAll();

        return $this->render('contacts/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/contacts/{id}', requirements: ['id' => "\d+"], name: 'contacts.show', methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id): Response
    {
        $contact = $em->getRepository(Contact::class)->find($id);

        return $this->render('contacts/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/contacts/create', name: 'contacts.create', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('contacts.index');
        }

        return $this->render('contacts/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/contacts/{id}/edit', name: 'contacts.edit', methods: ['GET', 'PATCH'])]
    public function edit(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $contact = $em->getRepository(Contact::class)->find($id);

        $form = $this->createForm(ContactForm::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            if ($contact instanceof Contact) {
                $em->persist($contact);
                $em->flush();
            }

            $this->addFlash('success', 'Contact successfully updated!');

            return $this->redirectToRoute('contacts.index');
        }

        return $this->render('contacts/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/contacts/{id}', name: 'contacts.delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, int $id): Response
    {
        $contact = $em->getRepository(Contact::class)->find($id);
        if ($contact) {
            $em->remove($contact);
            $em->flush();
        }

        $this->addFlash('success', 'Contact successfully deleted!');

        return $this->redirectToRoute('contacts.index');
    }
}
