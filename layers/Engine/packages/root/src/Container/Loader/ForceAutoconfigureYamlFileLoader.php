<?php

declare(strict_types=1);

namespace PoP\Root\Container\Loader;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ForceAutoconfigureYamlFileLoader extends YamlFileLoader
{
    public function __construct(
        ContainerBuilder $container,
        FileLocatorInterface $locator,
        protected bool $autoconfigure = true
    ) {
        parent::__construct($container, $locator);
    }

    /**
     * Override the Symfony class, to always inject the
     * "autoconfigure" property
     */
    protected function loadFile(string $file): ?array
    {
        $content = parent::loadFile($file);
        $content['services']['_defaults']['autoconfigure'] = $this->autoconfigure;
        return $content;
    }
}
