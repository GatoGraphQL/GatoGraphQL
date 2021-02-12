<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Conditional\CacheControl;

use PoP\Root\Component\InitializeContainerServicesInComponentTrait;
use PoPSchema\UserStateAccessControl\Component;

/**
 * Initialize component
 */
class ConditionalComponent
{
    use InitializeContainerServicesInComponentTrait;

    public static function initialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        self::maybeInitYAMLSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/CacheControl');
    }
}
