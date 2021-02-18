<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;
use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterAccessControlRuleBlockCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return AccessControlRuleBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return AbstractAccessControlRuleBlock::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addAccessControlRuleBlock';
    }
}
