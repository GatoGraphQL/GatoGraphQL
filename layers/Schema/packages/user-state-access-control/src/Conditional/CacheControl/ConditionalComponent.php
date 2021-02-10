<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Conditional\CacheControl;

use PoP\Root\Component\YAMLServicesTrait;
use PoPSchema\UserStateAccessControl\Component;

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
        self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/CacheControl');
    }
}
