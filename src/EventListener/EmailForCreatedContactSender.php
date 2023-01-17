<?php

namespace App\EventListener;

use App\Entity\Contact;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailForCreatedContactSender
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
                    ->from('hello@example.com')
                    ->to('you@example.com')
                    ->subject('Time for Symfony Mailer!')
                    ->htmlTemplate('emails/contact_created.html.twig')
                    ->context([
                        'contact' => $entity,
                    ]);

                $this->mailer->send($email);
            }
        }
    }
}
