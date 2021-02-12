<?php

declare(strict_types=1);

namespace PoP\API\Conditional\AccessControl;

use PoP\API\Component;
use PoP\AccessControl\ComponentConfiguration;
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
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/AccessControl');
        if (ComponentConfiguration::canSchemaBePrivate()) {
            self::maybeInitPHPSchemaServices(Component::$COMPONENT_DIR, $skipSchema, '/Conditional/AccessControl/ConditionalOnEnvironment/PrivateSchema');
        }
    }
}
