<?php

declare(strict_types=1);

namespace PoPSchema\Events\Conditional\Users;

use PoP\ComponentModel\Container\ContainerBuilderUtils;

/**
 * Initialize component
 */
class ComponentBoot
{
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers');
    }
}
