<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use WP_Post;

abstract class AbstractClientEndpointAnnotator extends AbstractEndpointAnnotator implements ClientEndpointAnnotatorInterface
{
    protected BlockHelpers $blockHelpers;
    protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType;

    #[Required]
    public function autowireAbstractClientEndpointAnnotator(
        BlockHelpers $blockHelpers,
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ): void {
        $this->blockHelpers = $blockHelpers;
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
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
