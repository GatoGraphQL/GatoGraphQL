<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractConfigureSchemaNamespacingCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $schemaNamespacingServiceDefinition = $containerBuilderWrapper->getDefinition(SchemaNamespacingServiceInterface::class);
        $schemaNamespace = $this->getSchemaNamespace();
        foreach ($this->getModuleClasses() as $moduleClass) {
            $pos = strrpos($moduleClass, '\\');
            if ($pos === false) {
                throw new ShouldNotHappenException(
                    sprintf(
                        'Module class \'%s\' has no namespace!',
                        $moduleClass
                    )
                );
            }
            $moduleClassNamespace = substr($moduleClass, 0, $pos);
            $schemaNamespacingServiceDefinition->addMethodCall(
                'addSchemaNamespaceForClassOwnerAndProjectNamespace',
                [
                    $moduleClassNamespace,
                    $schemaNamespace
                ]
            );
        }
    }

    /**
     * @return string[]
     * @phpstan-return array<class-string<ModuleInterface>>
     */
    abstract protected function getModuleClasses(): array;
    abstract protected function getSchemaNamespace(): string;
}
