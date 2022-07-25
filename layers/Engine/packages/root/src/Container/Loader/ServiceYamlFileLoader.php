<?php

declare(strict_types=1);

namespace PoP\Root\Container\Loader;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ServiceYamlFileLoader extends YamlFileLoader
{
    public function __construct(
        ContainerBuilder $container,
        FileLocatorInterface $locator,
        protected bool $autoconfigure = true
    ) {
        parent::__construct($container, $locator);
    }

    protected function loadFile(string $file): ?array
    {
        $content = parent::loadFile($file);
        return $content;
    }
}
