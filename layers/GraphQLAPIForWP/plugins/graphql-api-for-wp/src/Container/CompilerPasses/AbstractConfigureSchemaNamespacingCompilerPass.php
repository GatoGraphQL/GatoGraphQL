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
        foreach ($this->getComponentClasses() as $moduleClass) {
            $componentClassNamespace = substr($moduleClass, 0, strrpos($moduleClass, '\\'));
            $schemaNamespacingServiceDefinition->addMethodCall(
                'addSchemaNamespaceForClassOwnerAndProjectNamespace',
                [
                    $componentClassNamespace,
                    $schemaNamespace
                ]
            );
        }
    }

    /**
     * @return string[]
     */
    abstract protected function getComponentClasses(): array;
    abstract protected function getSchemaNamespace(): string;
}
