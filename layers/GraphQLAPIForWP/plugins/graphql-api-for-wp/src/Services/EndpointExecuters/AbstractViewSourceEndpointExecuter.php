<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\PluginApp;
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
        $customPost = App::getState(['routing', 'queried-object']);
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
        // Commented out Prettify
        // // $scriptSrc = 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js'
        // $mainPluginURL = \GraphQLAPI\GraphQLAPI\PluginApp::getMainPlugin()->getPluginURL();
        // $scriptSrc = $mainPluginURL . 'assets/js/vendors/code-prettify/run_prettify.js';
        // /**
        //  * Prettyprint the code
        //  */
        // $content .= sprintf(
        //     '<script src="%s"></script>',
        //     $scriptSrc
        // );

        /**
         * Using highlight.js
         *
         * @see https://highlightjs.org/usage/
         */
        $linkTagPlaceholder = '<link rel="stylesheet" href="%s">';
        $scriptTagPlaceholder = '<script src="%s"></script>';
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $content .= sprintf(
            $linkTagPlaceholder,
            $mainPluginURL . 'assets/css/vendors/highlight-11.6.0/a11y-dark.min.css'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/highlight.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/graphql.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/json.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/bash.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/run_highlight.js'
        );

        return $content;
    }
}
