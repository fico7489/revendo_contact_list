<?php

namespace App\Controller\Backend\Contact;

use App\Entity\Contact;
use App\Entity\ContactProfilePhoto;
use App\Form\ContactProfilePhotoForm;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePhotoController extends AbstractController
{
    #[Route('/contacts/profile-photo/{id}/create', requirements: ['id' => "\d+"], name: 'backend.contacts.profilePhoto.create', methods: ['POST'])]
    public function create(int $id, EntityManagerInterface $em, FileUploader $fileUploader, Request $request): Response
    {
        /** @var Contact $contact */
        $contact = $em->getRepository(Contact::class)->find($id);

        $form = $this->createForm(ContactProfilePhotoForm::class, $contact, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            if ($contact instanceof Contact) {
                /* @var \Symfony\Component\HttpFoundation\File\UploadedFile $profilePhotoFile */
                $profilePhotoFile = $form->get('profilePhoto')->getData();

                $contactProfilePhoto = new ContactProfilePhoto();
                if ($profilePhotoFile instanceof UploadedFile) {
                    $contactProfilePhoto->setContact($contact);
                    $contactProfilePhoto->setMimeType((string) $profilePhotoFile->getMimeType());
                    $contactProfilePhoto->setSize((int) $profilePhotoFile->getSize());

                    $fileName = $fileUploader->upload($profilePhotoFile);

                    $contactProfilePhoto->setPath((string) $fileName);
                    $contactProfilePhoto->setName((string) $fileName);

                    if ($fileName) {
                        $em->persist($contactProfilePhoto);
                        $em->flush();
                    }
                }

                $this->addFlash('success', 'Contact profile photo successfully created!');

                return $this->redirectToRoute('backend.contacts.edit', ['id' => $contact->getId()]);
            }
        }
        $file = $request->files->get('contactProfilePhoto');

        return $this->render('backend/contacts/create.html.twig', [
            'form' => $form,
        ]);
    }
}
