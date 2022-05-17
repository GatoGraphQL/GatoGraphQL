<?php

declare(strict_types=1);

namespace GraphQLAPI\ExtensionDemo;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtensionModule;

/**
 * Initialize component
 */
class Module extends AbstractExtensionModule
{
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }
}
