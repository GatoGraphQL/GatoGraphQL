<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use PoP\Root\Module\ModuleInterface;

class GatoConfigureSchemaNamespacingCompilerPass extends AbstractConfigureSchemaNamespacingCompilerPass
{
    protected function getSchemaNamespace(): string
    {
        return 'Gato';
    }

    /**
     * @return string[]
     * @phpstan-return array<class-string<ModuleInterface>>
     */
    protected function getModuleClasses(): array
    {
        return [
            \GraphQLByPoP\GraphQLServer\Module::class,
            \PoP\ComponentModel\Module::class,
            \PoP\Engine\Module::class,
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }
}
