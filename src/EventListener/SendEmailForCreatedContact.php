<?php

namespace App\EventListener;

use App\Entity\Contact;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailForCreatedContact
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $ouw = $eventArgs->getObjectManager()->getUnitOfWork();

        foreach ($ouw->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Contact) {
                $email = (new TemplatedEmail())
                    ->from('revendo@revendo.com')
                    ->to((string) $entity->getEmail())
                    ->subject('Your contact is created!')
                    ->htmlTemplate('emails/contact_created.html.twig')
                    ->context([
                        'contact' => $entity,
                    ]);

                $this->mailer->send($email);
            }
        }
    }
}
