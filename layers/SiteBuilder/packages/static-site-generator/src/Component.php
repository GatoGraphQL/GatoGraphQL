<?php

declare(strict_types=1);

namespace PoP\SSG;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\Site\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'getpop/migrate-static-site-generator',
        ];
    }
}
