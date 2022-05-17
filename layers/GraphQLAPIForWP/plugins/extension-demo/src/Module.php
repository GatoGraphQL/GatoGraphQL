<?php

declare(strict_types=1);

namespace GraphQLAPI\ExtensionDemo;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtensionModule;

class Module extends AbstractExtensionModule
{
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }
}
