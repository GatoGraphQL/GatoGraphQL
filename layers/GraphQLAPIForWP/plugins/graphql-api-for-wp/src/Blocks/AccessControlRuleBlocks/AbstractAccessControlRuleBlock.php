<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\AccessControlBlockCategory;

/**
 * Access Control rule block
 */
abstract class AbstractAccessControlRuleBlock extends AbstractBlock
{
    public const ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP = 'accessControlGroup';
    public const ATTRIBUTE_NAME_VALUE = 'value';

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var AccessControlBlockCategory
         */
        $blockCategory = $instanceManager->getInstance(AccessControlBlockCategory::class);
        return $blockCategory;
    }
}
