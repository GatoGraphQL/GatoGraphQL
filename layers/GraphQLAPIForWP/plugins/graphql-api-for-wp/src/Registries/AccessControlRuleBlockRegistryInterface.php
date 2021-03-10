<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;

interface AccessControlRuleBlockRegistryInterface
{
    public function addAccessControlRuleBlock(AbstractAccessControlRuleBlock $accessControlRuleBlock): void;
    /**
     * @return AbstractAccessControlRuleBlock[]
     */
    public function getAccessControlRuleBlocks(): array;
}
