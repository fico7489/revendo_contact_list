<?php

declare(strict_types=1);

namespace App\Vendor\Alice\Locator;

use Hautelook\AliceBundle\FixtureLocatorInterface;
use Nelmio\Alice\IsAServiceTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

final class CustomOrderFilesLocator implements FixtureLocatorInterface
{
    use IsAServiceTrait;

    private FixtureLocatorInterface $decoratedFixtureLocator;

    public function __construct(FixtureLocatorInterface $decoratedFixtureLocator)
    {
        $this->decoratedFixtureLocator = $decoratedFixtureLocator;
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

        $finder->in('public/uploads/contact-profile-photos')->depth(0);
        $items = \iterator_to_array($finder);

        $filesystem = new Filesystem();
        foreach ($items as $path => $item) {
            $filesystem->remove($path);
        }
    }
}
