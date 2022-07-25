<?php

declare(strict_types=1);

namespace PoP\Root\Container\Loader;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Override the Symfony class, to:
 *
 * - always inject the "autoconfigure" property
 * - add the required tag "container.ignore_attributes" to avoid PHP 8's attributes
 */
class SchemaServiceYamlFileLoader extends YamlFileLoader
{
    use ServiceYamlFileLoaderTrait;

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
        if ($content === null) {
            return null;
        }

        $content['services']['_defaults']['autoconfigure'] = $this->autoconfigure;
        return $this->customizeYamlFileDefinition($content);
    }
}
