<?php

namespace App\Vendor\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Base as BaseProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ContactProfilePhotoProvider extends BaseProvider
{
    private ParameterBagInterface $parameters;

    public function __construct(ParameterBagInterface $parameters, Generator $generator)
    {
        $this->parameters = $parameters;

        parent::__construct($generator);
    }

    public function contactProfilePhotoName(): string
    {
        $randomInt = random_int(1, 10);

        $pathBase = $randomInt;
        $pathCurrent = 'assets/fixtures/contacts/'.$pathBase.'.jpg';
        $name = $pathBase.'-'.sha1(time().self::randomDigit()).'.jpg';

        /** @var string $uploadDirectory */
        $uploadDirectory = $this->parameters->get('contact_profile_photos_directory');
        $pathUpload = $uploadDirectory.$name;

        $filesystem = new Filesystem();

        $filesystem->copy($pathCurrent, $pathUpload);

        return $name;
    }
}
