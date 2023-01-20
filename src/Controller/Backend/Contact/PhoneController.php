<?php

namespace App\Controller\Backend\Contact;

use App\Entity\Contact;
use App\Form\Contact\PhonesForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    #[Route('/contacts/phone/{id}/edit', requirements: ['id' => "\d+"], name: 'backend.contacts.phone.edit', methods: ['POST'])]
    #[Entity('contact', options: ['id' => 'id'])]
    public function edit(Contact $contact, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(PhonesForm::class, $contact, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();

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
            $contactPhones = $contact->getContactPhones();
            foreach ($contactPhones as $contactPhone) {
                $contactPhone->setContact($contact);
            }

            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Contact phones successfully updated!');

            return $this->redirectToRoute('backend.contacts.edit', ['id' => $contact->getId()]);
        }

        $this->addFlash('error', 'Validation error!');

        return $this->redirectToRoute('backend.contacts.edit', ['id' => $contact->getId()]);
    }
}
