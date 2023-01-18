<?php

namespace App\Vendor\Faker\Provider;

use Faker\Provider\Base as BaseProvider;
use Symfony\Component\Filesystem\Filesystem;

final class ContactProfilePhotoProvider extends BaseProvider
{
    public function contactProfilePhotoName(): string
    {
        $randomInt = random_int(1, 10);

        $pathBase = $randomInt;
        $pathCurrent = 'assets/fixtures/contacts/'.$pathBase.'.jpg';
        $name = $pathBase.'-'.sha1(time().self::randomDigit()).'.jpg';
        $pathUpload = 'public/uploads/contact-profile-photos/'.$name;

        $filesystem = new Filesystem();

        $filesystem->copy($pathCurrent, $pathUpload);

        return $name;
    }
}
