<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapperInterface;

abstract class AbstractInjectServiceIntoRegistryCompilerPass extends AbstractCompilerPass
{
    /**
     * Build the deterministic tag name for a service interface.
     * Modules registering autoconfigurations and compiler passes looking
     * services up MUST agree on this derivation.
     */
    final public static function tagForInterface(string $interfaceClass): string
    {
        return 'pop.auto.' . strtr($interfaceClass, ['\\' => '.']);
    }

    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $registryDefinition = $containerBuilderWrapper->getDefinition($this->getRegistryServiceDefinition());
        $methodName = $this->getRegistryMethodCallName();
        $tag = self::tagForInterface($this->getServiceClass());
        foreach ($containerBuilderWrapper->findTaggedServiceIds($tag) as $definitionID => $_attributes) {
            $registryDefinition->addMethodCall(
                $methodName,
                [
                    $this->createReference($definitionID),
                    $definitionID,
                ]
            );
        }
    }

    abstract protected function getRegistryServiceDefinition(): string;
    abstract protected function getServiceClass(): string;
    abstract protected function getRegistryMethodCallName(): string;
}
