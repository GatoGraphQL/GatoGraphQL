<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;

abstract class AbstractConfigureSchemaNamespacingCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $schemaNamespacingServiceDefinition = $containerBuilderWrapper->getDefinition(SchemaNamespacingServiceInterface::class);
        $schemaNamespace = $this->getSchemaNamespace();
        foreach ($this->getModuleClasses() as $moduleClass) {
            $moduleClassNamespace = substr($moduleClass, 0, strrpos($moduleClass, '\\'));
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
     */
    abstract protected function getModuleClasses(): array;
    abstract protected function getSchemaNamespace(): string;
}
