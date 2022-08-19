<?php

declare(strict_types=1);

namespace PoP\Root\Container\Loader;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Override the Symfony class, to add the required tag
 * "container.ignore_attributes" to avoid PHP 8's attributes.
 * Otherwise, an exception is produced when the plugin
 * is downgraded to PHP 7.*
 *
 * @see https://github.com/symfony/symfony/blob/6fef5b44b0ed982c6c930da2b435179662f8fd25/src/Symfony/Component/DependencyInjection/Compiler/RegisterAutoconfigureAttributesPass.php#L19-L25
 */
class ServiceYamlFileLoader extends YamlFileLoader
{
    use ServiceYamlFileLoaderTrait;

    /**
     * @return mixed[]
     */
    protected function loadFile(string $file): ?array
    {
        $content = parent::loadFile($file);
        if ($content === null) {
            return null;
        }

        return $this->customizeYamlFileDefinition($content);
    }
}
