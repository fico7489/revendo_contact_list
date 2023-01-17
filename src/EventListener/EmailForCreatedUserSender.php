<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailForCreatedUserSender
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
            if ($entity instanceof User) {
                $email = (new TemplatedEmail())
                    ->from('hello@example.com')
                    ->to('you@example.com')
                    ->subject('Time for Symfony Mailer!')
                    ->htmlTemplate('emails/contact_created.html.twig')
                    ->context([
                        'user' => $entity,
                    ]);

                $this->mailer->send($email);
            }
        }
    }
}
