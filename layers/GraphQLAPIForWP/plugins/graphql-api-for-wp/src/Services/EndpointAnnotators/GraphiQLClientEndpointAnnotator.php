<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use WP_Post;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;

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
        // Check the endpoint in the post is not disabled
        /** @var GraphQLCustomEndpointCustomPostType */
        $customPostType = $this->getCustomPostType();
        if (!$customPostType->isEndpointEnabled($post)) {
            return;
        }

        // Check the client has not been disabled in the CPT
        if (!$customPostType->isGraphiQLEnabled($post)) {
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
}
