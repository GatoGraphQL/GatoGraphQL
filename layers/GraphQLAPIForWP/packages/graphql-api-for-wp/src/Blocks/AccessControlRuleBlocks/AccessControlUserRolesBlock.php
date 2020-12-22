<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AbstractItemListAccessControlRuleBlock;

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

    protected function getHeader(): string
    {
        return __('Users with any of these roles', 'graphql-api');
    }
}
