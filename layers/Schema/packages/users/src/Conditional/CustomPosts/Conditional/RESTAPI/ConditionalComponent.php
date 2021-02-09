<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\Conditional\RESTAPI;

use PoPSchema\Users\Component;
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
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/CustomPosts/Conditional/RESTAPI');
    }
}
