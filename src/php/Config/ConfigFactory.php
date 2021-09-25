<?php

declare(strict_types=1);

namespace Phel\Config;

use Gacela\Framework\AbstractFactory;
use Phel\Config\Finder\ComposerVendorDirectoriesFinder;
use Phel\Config\Finder\PhelFileFinder;
use Phel\Config\Finder\PhelFileFinderInterface;
use Phel\Config\Finder\VendorDirectoriesFinderInterface;

/**
 * @method ConfigConfig getConfig()
 */
final class ConfigFactory extends AbstractFactory
{
    public function getVendorDirectoryFinder(): VendorDirectoriesFinderInterface
    {
        return new ComposerVendorDirectoriesFinder($this->getConfig()->getVendorDir());
    }

    public function getPhelFileFinder(): PhelFileFinderInterface
    {
        return new PhelFileFinder();
    }

    public function getPhelConfig(): ConfigConfig
    {
        return $this->getConfig();
    }
}
