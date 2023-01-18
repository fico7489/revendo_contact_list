<?php

namespace App\Controller\Backend\Contact;

use App\Entity\Contact;
use App\Entity\ContactPhone;
use App\Form\Contact\PhonesForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    #[Route('/contacts/phone/{id}/create', requirements: ['id' => "\d+"], name: 'backend.contacts.phone.create', methods: ['POST'])]
    public function create(int $id, EntityManagerInterface $em, ParameterBagInterface $parameters, Request $request): Response
    {
        /** @var Contact $contact */
        $contact = $em->getRepository(Contact::class)->find($id);

        $form = $this->createForm(PhonesForm::class, $contact, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            if ($contact instanceof Contact) {
                // dd($contact->getContactPhones()->toArray());
                $originalContactPhones = new ArrayCollection();

                foreach ($contact->getContactPhones() as $contactPhone) {
                    $originalContactPhones->add($contactPhone);
                }

                // remove phones
                foreach ($originalContactPhones as $originalContactPhone) {
                    if (false === $contact->getContactPhones()->contains($originalContactPhone)) {
                        $contact->getContactPhones()->removeElement($originalContactPhone);
                    }
                }

                // persist new added phones
                foreach ($contact->getContactPhones() as $contactPhone) {
                    if ($contactPhone instanceof ContactPhone) {
                        $contactPhone->setContact($contact);
                        $em->persist($contactPhone);
                    }
                }

                $em->persist($contact);
                $em->flush();

                $this->addFlash('success', 'Contact phones successfully updated!');

                return $this->redirectToRoute('backend.contacts.edit', ['id' => $contact->getId()]);
            }
        }

        // TODO show error

        return $this->render('backend/contacts/create.html.twig', [
            'form' => $form,
        ]);
    }
}
