<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Conditional\Users;

use PoPSchema\Posts\Component;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\Root\Container\ContainerBuilderUtils;

/**
 * Initialize component
 */
class ConditionalComponent
{
    use YAMLServicesTrait;

    public static function initialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/Users');
        self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/Users');
    }
}
