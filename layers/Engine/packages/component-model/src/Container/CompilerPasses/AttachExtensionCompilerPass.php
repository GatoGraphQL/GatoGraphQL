<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AttachExtensionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
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
                    [$definitionClass, $attachableGroup]
                );
                // A service won't be of 2 attachable classes, so can skip checking
                continue(2);
            }
        }
    }

    /**
     * @return array<string,string>
     */
    protected function getAttachableClassGroups(): array
    {
        return [
            FieldResolverInterface::class => AttachableExtensionGroups::FIELDRESOLVERS,
            DirectiveResolverInterface::class => AttachableExtensionGroups::DIRECTIVERESOLVERS,
            TypeResolverPickerInterface::class => AttachableExtensionGroups::TYPERESOLVERPICKERS,
        ];
    }
}
