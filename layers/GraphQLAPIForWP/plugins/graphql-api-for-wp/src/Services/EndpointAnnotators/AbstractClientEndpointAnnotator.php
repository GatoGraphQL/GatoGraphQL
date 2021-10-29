<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use Symfony\Contracts\Service\Attribute\Required;
use WP_Post;

abstract class AbstractClientEndpointAnnotator extends AbstractEndpointAnnotator implements ClientEndpointAnnotatorInterface
{
    private ?BlockHelpers $blockHelpers = null;
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    protected function getBlockHelpers(): BlockHelpers
    {
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
    }
    public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    //#[Required]
    final public function autowireAbstractClientEndpointAnnotator(
        BlockHelpers $blockHelpers,
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ): void {
        $this->blockHelpers = $blockHelpers;
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLCustomEndpointCustomPostType();
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
        $optionsBlockDataItem = $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
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

    abstract protected function getBlock(): BlockInterface;

    protected function getIsEnabledAttributeName(): string
    {
        return BlockAttributeNames::IS_ENABLED;
    }
}
