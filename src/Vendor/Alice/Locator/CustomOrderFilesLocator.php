<?php

declare(strict_types=1);

namespace App\Vendor\Alice\Locator;

use Hautelook\AliceBundle\FixtureLocatorInterface;
use Nelmio\Alice\IsAServiceTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

final class CustomOrderFilesLocator implements FixtureLocatorInterface
{
    use IsAServiceTrait;

    private FixtureLocatorInterface $decoratedFixtureLocator;
    private ParameterBagInterface $parameters;

    public function __construct(FixtureLocatorInterface $decoratedFixtureLocator, ParameterBagInterface $parameters)
    {
        $this->decoratedFixtureLocator = $decoratedFixtureLocator;
        $this->parameters = $parameters;
    }

    /**
     * Re-order the files found by the decorated finder.
     *
     * {@inheritdoc}
     */
    public function locateFiles(array $bundles, string $environment): array
    {
        $files = $this->decoratedFixtureLocator->locateFiles($bundles, $environment);

        $this->clearContactProfilePhotos();

        return $files;
    }

    private function clearContactProfilePhotos(): void
    {
        $finder = Finder::create();

        /** @var string $uploadDirectory */
        $uploadDirectory = $this->parameters->get('app.contact_profile_photos_directory');
        $finder->in($uploadDirectory)->depth(0);
        $items = \iterator_to_array($finder);

        $filesystem = new Filesystem();
        foreach ($items as $path => $item) {
            $filesystem->remove($path);
        }
    }
}
