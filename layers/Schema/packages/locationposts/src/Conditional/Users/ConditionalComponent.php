<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts\Conditional\Users;

use PoPSchema\LocationPosts\Component;
use PoP\Root\Component\YAMLServicesTrait;

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
        self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/Users');
    }
}
