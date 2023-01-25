<?php

namespace App\EventListener;

use App\Entity\ContactProfilePhoto;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class RemoveFileForDeletedContact
{
    private ParameterBagInterface $parameters;

    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    public function postRemove(PostRemoveEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof ContactProfilePhoto) {
            $filename = $entity->getPath();

            /** @var string $uploadDirectory */
            $uploadDirectory = $this->parameters->get('app.contact_profile_photos_directory');
            $filesystem = new Filesystem();
            $filesystem->remove($uploadDirectory.$filename);
        }
    }
}
