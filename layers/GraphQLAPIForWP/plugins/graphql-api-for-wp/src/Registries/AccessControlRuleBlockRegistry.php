<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;

class AccessControlRuleBlockRegistry implements AccessControlRuleBlockRegistryInterface
{
    /**
     * @var AbstractAccessControlRuleBlock[]
     */
    protected array $accessControlRuleBlocks = [];

    public function addAccessControlRuleBlock(AbstractAccessControlRuleBlock $accessControlRuleBlock): void
    {
        $this->accessControlRuleBlocks[] = $accessControlRuleBlock;
    }
    /**
     * @return AbstractAccessControlRuleBlock[]
     */
    public function getAccessControlRuleBlocks(): array
    {
        return $this->accessControlRuleBlocks;
    }
}
