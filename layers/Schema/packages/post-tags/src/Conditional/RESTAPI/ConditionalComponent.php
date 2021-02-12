<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Conditional\RESTAPI;

use PoPSchema\PostTags\Component;
use PoP\Root\Component\InitializeContainerServicesInComponentTrait;

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
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/RESTAPI');
    }
}
