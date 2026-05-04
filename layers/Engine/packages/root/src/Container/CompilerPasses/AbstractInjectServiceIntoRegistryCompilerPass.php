<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\ContainerBuilderWrapperInterface;
use Symfony\Component\DependencyInjection\Definition;

abstract class AbstractInjectServiceIntoRegistryCompilerPass extends AbstractCompilerPass
{
    use AutoconfigurableServicesCompilerPassTrait;

    /**
     * Container parameter holding the list of interfaces for which an
     * autoconfiguration tag has been registered. Used to decide between
     * the fast (tag-based) path and the legacy (class-scanning) fallback
     * during the staged migration.
     */
    public const PARAM_REGISTERED_AUTOCONFIGURATIONS = 'pop.registered_autoconfigurations';

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
        $serviceClass = $this->getServiceClass();

        if ($this->isAutoconfigurationRegistered($containerBuilderWrapper, $serviceClass)) {
            $this->collectViaTag($containerBuilderWrapper, $registryDefinition, $serviceClass);
            return;
        }

        // Fallback: legacy class scan. Kept for staged migration; remove
        // once every subclass's interface has been moved to autoconfiguration.
        $this->collectViaScan($containerBuilderWrapper, $registryDefinition, $serviceClass);
    }

    private function isAutoconfigurationRegistered(
        ContainerBuilderWrapperInterface $containerBuilderWrapper,
        string $serviceClass
    ): bool {
        $containerBuilder = $containerBuilderWrapper->getContainerBuilder();
        if (!$containerBuilder->hasParameter(self::PARAM_REGISTERED_AUTOCONFIGURATIONS)) {
            return false;
        }
        /** @var array<class-string,bool> */
        $registered = $containerBuilder->getParameter(self::PARAM_REGISTERED_AUTOCONFIGURATIONS);
        return isset($registered[$serviceClass]);
    }

    private function collectViaTag(
        ContainerBuilderWrapperInterface $containerBuilderWrapper,
        Definition $registryDefinition,
        string $serviceClass
    ): void {
        $tag = self::tagForInterface($serviceClass);
        foreach ($containerBuilderWrapper->findTaggedServiceIds($tag) as $definitionID => $_attributes) {
            $registryDefinition->addMethodCall(
                $this->getRegistryMethodCallName(),
                [
                    $this->createReference($definitionID),
                    $definitionID,
                ]
            );
        }
    }

    private function collectViaScan(
        ContainerBuilderWrapperInterface $containerBuilderWrapper,
        Definition $registryDefinition,
        string $serviceClass
    ): void {
        $onlyProcessAutoconfiguredServices = $this->onlyProcessAutoconfiguredServices();
        foreach ($containerBuilderWrapper->getDefinitions() as $definitionID => $definition) {
            $definitionClass = $definition->getClass();
            if (
                $definitionClass === null
                || !is_a($definitionClass, $serviceClass, true)
            ) {
                continue;
            }
            if ($onlyProcessAutoconfiguredServices && !$definition->isAutoconfigured()) {
                continue;
            }
            $registryDefinition->addMethodCall(
                $this->getRegistryMethodCallName(),
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
