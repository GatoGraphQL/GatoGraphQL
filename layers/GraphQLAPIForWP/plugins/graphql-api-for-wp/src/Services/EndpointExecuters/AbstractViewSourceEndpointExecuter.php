<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterServiceTagInterface;
use WP_Post;

abstract class AbstractViewSourceEndpointExecuter extends AbstractCPTEndpointExecuter implements EndpointExecuterServiceTagInterface
{
    protected function getView(): string
    {
        return RequestParams::VIEW_SOURCE;
    }

    public function executeEndpoint(): void
    {
        /** Add the excerpt, which is the description of the GraphQL query */
        \add_filter(
            'the_content',
            $this->maybeGetGraphQLQuerySourceContent(...)
        );
    }

    /**
     * Render the GraphQL Query CPT
     */
    public function maybeGetGraphQLQuerySourceContent(string $content): string
    {
        $customPost = \PoP\Root\App::getState(['routing', 'queried-object']);
        // Make sure there is a post (eg: it has not been deleted)
        if ($customPost !== null) {
            return $this->getGraphQLQuerySourceContent($content, $customPost);
        }
        return $content;
    }

    /**
     * Render the GraphQL Query CPT
     */
    protected function getGraphQLQuerySourceContent(string $content, WP_Post $graphQLQueryPost): string
    {
        // $scriptSrc = 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js'
        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $scriptSrc = $mainPluginURL . 'assets/js/vendors/code-prettify/run_prettify.js';
        /**
         * Prettyprint the code
         */
        $content .= sprintf(
            '<script src="%s"></script>',
            $scriptSrc
        );
        return $content;
    }
}
