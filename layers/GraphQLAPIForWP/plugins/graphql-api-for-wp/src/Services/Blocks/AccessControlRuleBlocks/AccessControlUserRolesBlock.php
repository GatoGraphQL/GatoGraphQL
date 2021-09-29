<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;

/**
 * Access Control User Roles block
 */
class AccessControlUserRolesBlock extends AbstractItemListAccessControlRuleBlock
{
    use MainPluginBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-roles';
    }

    public function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_ROLES;
    }

    protected function getHeader(): string
    {
        return __('Users with any of these roles', 'graphql-api');
    }
}
