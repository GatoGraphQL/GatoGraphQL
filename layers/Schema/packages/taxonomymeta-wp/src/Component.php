<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMetaWP;

use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\TaxonomyMeta\Component::class,
            \PoPSchema\TaxonomiesWP\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'pop-schema/migrate-taxonomymeta-wp',
        ];
    }
}
