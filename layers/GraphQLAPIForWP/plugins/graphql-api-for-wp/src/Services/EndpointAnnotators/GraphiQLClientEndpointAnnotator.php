<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use WP_Post;

class GraphiQLClientEndpointAnnotator extends AbstractClientEndpointAnnotator implements CustomEndpointAnnotatorServiceTagInterface
{
    public function getEnablingModule(): ?string
    {
        return ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS;
    }

    /**
     * Add actions to the CPT list
     * @param array<string, string> $actions
     */
    public function addCustomPostTypeTableActions(array &$actions, WP_Post $post): void
    {
        // Check the client has not been disabled in the CPT
        if (!$this->isClientEnabled($post)) {
            return;
        }

        if ($permalink = \get_permalink($post->ID)) {
            $title = \_draft_or_post_title();
            $actions['graphiql'] = sprintf(
                '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                \add_query_arg(
                    RequestParams::VIEW,
                    RequestParams::VIEW_GRAPHIQL,
                    $permalink
                ),
                /* translators: %s: Post title. */
                \esc_attr(\sprintf(\__('GraphiQL &#8220;%s&#8221;'), $title)),
                __('GraphiQL', 'graphql-api')
            );
        }
    }

    /**
     * Read the options block and check the value of attribute "isGraphiQLEnabled"
     */
    public function isClientEnabled(WP_Post|int $postOrID): bool
    {
        // Check the endpoint in the post is not disabled
        /** @var GraphQLCustomEndpointCustomPostType */
        $customPostType = $this->getCustomPostType();
        if (!$customPostType->isEndpointEnabled($postOrID)) {
            return false;
        }

        /** @var BlockHelpers */
        $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
        /** @var EndpointGraphiQLBlock */
        $endpointGraphiQLBlock = $this->instanceManager->getInstance(EndpointGraphiQLBlock::class);
        $optionsBlockDataItem = $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $postOrID,
            $endpointGraphiQLBlock
        );

        // If there was no options block, something went wrong in the post content
        $default = true;
        if (is_null($optionsBlockDataItem)) {
            return $default;
        }

        // The default value is not saved in the DB in Gutenberg!
        $attribute = EndpointGraphiQLBlock::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED;
        return $optionsBlockDataItem['attrs'][$attribute] ?? $default;
    }
}
