<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AbstractItemListAccessControlRuleBlock;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AccessControlFunctionalityModuleResolver;

/**
 * Access Control User Capabilities block
 */
class AccessControlUserCapabilitiesBlock extends AbstractItemListAccessControlRuleBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-capabilities';
    }

    protected function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_CAPABILITIES;
    }

    protected function getHeader(): string
    {
        return __('Users with any of these capabilities', 'graphql-api');
    }
}
