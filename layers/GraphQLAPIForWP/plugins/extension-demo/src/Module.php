<?php

declare(strict_types=1);

namespace GraphQLAPI\ExtensionDemo;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtensionComponent;

/**
 * Initialize component
 */
class Module extends AbstractExtensionComponent
{
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }
}
