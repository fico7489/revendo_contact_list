<?php

namespace App\Controller\Backend\Contact;

use App\Entity\Contact;
use App\Entity\ContactProfilePhoto;
use App\Form\Contact\ProfilePhotoForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePhotoController extends AbstractController
{
    #[Route('/contacts/{id}/profile-photo//create', requirements: ['id' => "\d+"], name: 'backend.contacts.profilePhoto.create', methods: ['POST'])]
    public function create(int $id, EntityManagerInterface $em, ParameterBagInterface $parameters, Request $request): Response
    {
        /** @var Contact $contact */
        $contact = $em->getRepository(Contact::class)->find($id);

        $form = $this->createForm(ProfilePhotoForm::class, $contact, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            if ($contact instanceof Contact) {
                /* @var \Symfony\Component\HttpFoundation\File\UploadedFile $profilePhotoFile */
                $profilePhotoFile = $form->get('profilePhoto')->getData();

                $contactProfilePhoto = new ContactProfilePhoto();
                if ($profilePhotoFile instanceof UploadedFile) {
                    $clientOriginalName = $profilePhotoFile->getClientOriginalName();

                    $contactProfilePhoto->setContact($contact);
                    $contactProfilePhoto->setMimeType((string) $profilePhotoFile->getMimeType());
                    $contactProfilePhoto->setSize((int) $profilePhotoFile->getSize());
                    $contactProfilePhoto->setPath((string) $clientOriginalName);
                    $contactProfilePhoto->setName((string) $clientOriginalName);

                    /** @var string $uploadDirectory */
                    $uploadDirectory = $parameters->get('contact_profile_photos_directory');
                    $fileMoved = $profilePhotoFile->move($uploadDirectory, $clientOriginalName);

                    $em->persist($contactProfilePhoto);
                    $em->flush();
                }

                $this->addFlash('success', 'Contact profile photo successfully created!');

                return $this->redirectToRoute('backend.contacts.edit', ['id' => $contact->getId()]);
            }
        }
        // TODO show error

        return $this->render('backend/contacts/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/contacts/{id}/profile-photo/{id2}', requirements: ['id' => "\d+"], name: 'backend.contacts.profilePhoto.delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, int $id, int $id2): Response
    {
        $contact = $em->getRepository(Contact::class)->find($id);
        if ($contact) {
            $contact->setContactProfilePhoto(null);
            $em->persist($contact);
            $em->flush();
        }

        $this->addFlash('success', 'Contact successfully deleted!');

        return $this->redirectToRoute('backend.contacts.index');
    }
}
