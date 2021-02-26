<?php

declare(strict_types=1);

namespace PoP\Root\Container\Loader;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ForceAutoconfigureYamlFileLoader extends YamlFileLoader
{
    protected bool $autoconfigure;

    public function __construct(
        ContainerBuilder $container,
        FileLocatorInterface $locator,
        ?bool $autoconfigure = true
    ) {
        parent::__construct($container, $locator);
        $this->autoconfigure = $autoconfigure;
    }

    /**
     * Override the Symfony class, to always inject the
     * "autoconfigure" property
     */
    protected function loadFile($file)
    {
        $content = parent::loadFile($file);
        $content['services']['_defaults']['autoconfigure'] = $this->autoconfigure;
        return $content;
    }
}
