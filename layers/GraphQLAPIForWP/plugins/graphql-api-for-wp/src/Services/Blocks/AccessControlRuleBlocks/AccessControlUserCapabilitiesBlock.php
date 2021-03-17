<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks\AbstractItemListAccessControlRuleBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;

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

    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_CAPABILITIES;
    }

    protected function getHeader(): string
    {
        return __('Users with any of these capabilities', 'graphql-api');
    }
}
