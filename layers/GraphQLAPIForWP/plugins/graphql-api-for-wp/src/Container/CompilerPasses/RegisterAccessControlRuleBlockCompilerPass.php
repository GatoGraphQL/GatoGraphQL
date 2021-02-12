<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;
use GraphQLAPI\GraphQLAPI\Services\Registries\AccessControlRuleBlockRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterAccessControlRuleBlockCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $accessControlRuleBlockRegistryDefinition = $containerBuilder->getDefinition(AccessControlRuleBlockRegistryInterface::class);
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, AbstractAccessControlRuleBlock::class, true)) {
                continue;
            }

            // Register the accessControlRuleBlock in the registry
            $accessControlRuleBlockRegistryDefinition->addMethodCall(
                'addServiceClass',
                [$definitionClass]
            );
        }
    }
}
