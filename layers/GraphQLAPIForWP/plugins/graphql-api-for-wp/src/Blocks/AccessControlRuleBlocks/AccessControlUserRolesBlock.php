<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AbstractItemListAccessControlRuleBlock;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AccessControlFunctionalityModuleResolver;

/**
 * Access Control User Roles block
 */
class AccessControlUserRolesBlock extends AbstractItemListAccessControlRuleBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-roles';
    }

    protected function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_ROLES;
    }

    protected function getHeader(): string
    {
        return __('Users with any of these roles', 'graphql-api');
    }
}
