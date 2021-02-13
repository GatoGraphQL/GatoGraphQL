<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractAttachExtensionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $event = $this->getAttachExtensionEvent();
        $attachableClassGroups = $this->getAttachableClassGroups();
        $attachExtensionServiceDefinition = $containerBuilder->getDefinition(AttachExtensionServiceInterface::class);
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null) {
                continue;
            }
            // Check if the service is of any attachable type
            foreach ($attachableClassGroups as $attachableClass => $attachableGroup) {
                if (!is_a($definitionClass, $attachableClass, true)) {
                    continue;
                }

                $attachExtensionServiceDefinition->addMethodCall(
                    'enqueueExtension',
                    [$event, $definitionClass, $attachableGroup]
                );
                // A service won't be of 2 attachable classes, so can skip checking
                continue(2);
            }
        }
    }

    abstract protected function getAttachExtensionEvent(): string;

    /**
     * @return array<string,string>
     */
    abstract protected function getAttachableClassGroups(): array;
}
