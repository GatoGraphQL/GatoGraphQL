<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use WP_Post;

abstract class AbstractClientEndpointAnnotator extends AbstractEndpointAnnotator implements ClientEndpointAnnotatorInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected BlockHelpers $blockHelpers,
        protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType;
    }

    /**
     * Read the options block and check the value of attribute "isGraphiQLEnabled"
     */
    public function isClientEnabled(WP_Post|int $postOrID): bool
    {
        // Check the endpoint in the post is not disabled
        if (!$this->getCustomPostType()->isEndpointEnabled($postOrID)) {
            return false;
        }

        // If there was no options block, something went wrong in the post content
        $default = true;
        $optionsBlockDataItem = $this->blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $this->getBlock()
        );
        if ($optionsBlockDataItem === null) {
            return $default;
        }

        // The default value is not saved in the DB in Gutenberg!
        $attribute = $this->getIsEnabledAttributeName();
        return $optionsBlockDataItem['attrs'][$attribute] ?? $default;
    }

    abstract protected function getBlock(): AbstractBlock;

    protected function getIsEnabledAttributeName(): string
    {
        return BlockAttributeNames::IS_ENABLED;
    }
}
