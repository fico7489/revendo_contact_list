<?php

namespace App\Controller\Backend;

use App\Entity\Contact;
use App\Form\Contact\PhonesForm;
use App\Form\Contact\ProfilePhotoForm;
use App\Form\ContactForm;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    #[Route('/contacts', name: 'backend.contacts.index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $q = is_string($q = $request->get('q')) ? $q : '';
        $favorite = (bool) (('1' == $request->get('favorite')) ? 1 : 0);
        $partial = (bool) (('1' == $request->get('partial')) ? 1 : 0);

        $contacts = $this->contactRepository->findBySearchQuery($favorite, $q);

        return $this->render('backend/contacts/'.($partial ? '_partials/index_table' : 'index').'.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/contacts/{id}', requirements: ['id' => "\d+"], name: 'backend.contacts.show', methods: ['GET'])]
    #[Entity('contact', options: ['id' => 'id'])]
    public function show(Contact $contact): Response
    {
        return $this->render('backend/contacts/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/contacts/create', name: 'backend.contacts.create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactForm::class, $contact, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();

            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Contact successfully created!');

            return $this->redirectToRoute('backend.contacts.edit', ['id' => $contact->getId()]);
        }

        return $this->render('backend/contacts/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/contacts/{id}/edit', requirements: ['id' => "\d+"], name: 'backend.contacts.edit', methods: ['GET', 'PATCH'])]
    #[Entity('contact', options: ['id' => 'id'])]
    public function edit(EntityManagerInterface $em, Request $request, Contact $contact): Response
    {
        $form = $this->createForm(ContactForm::class, $contact);
        $formContactProfilePhoto = $this->createForm(ProfilePhotoForm::class, $contact, ['method' => 'POST']);
        $formContactPhones = $this->createForm(PhonesForm::class, $contact, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();

            $contact->setFavorite($form->get('favorite')->isSubmitted());
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Contact successfully updated!');

            return $this->redirectToRoute('backend.contacts.edit', ['id' => $contact->getId()]);
        }

        return $this->render('backend/contacts/edit.html.twig', [
            'form' => $form,
            'formContactProfilePhoto' => $formContactProfilePhoto,
            'formContactPhones' => $formContactPhones,
            'contact' => $contact,
        ]);
    }

    #[Route('/contacts/{id}', requirements: ['id' => "\d+"], name: 'backend.contacts.delete', methods: ['DELETE'])]
    #[Entity('contact', options: ['id' => 'id'])]
    public function delete(EntityManagerInterface $em, Contact $contact): Response
    {
        $em->remove($contact);
        $em->flush();

        $this->addFlash('success', 'Contact successfully deleted!');

        return $this->redirectToRoute('backend.contacts.index');
    }
}
