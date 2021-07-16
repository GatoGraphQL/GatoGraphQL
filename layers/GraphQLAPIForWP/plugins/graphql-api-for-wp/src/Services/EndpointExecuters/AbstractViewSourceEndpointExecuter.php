<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use PoP\ComponentModel\State\ApplicationState;
use WP_Post;

abstract class AbstractViewSourceEndpointExecuter extends AbstractEndpointExecuter
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
            [$this, 'maybeGetGraphQLQuerySourceContent']
        );
    }

    /**
     * Render the GraphQL Query CPT
     */
    public function maybeGetGraphQLQuerySourceContent(string $content): string
    {
        $vars = ApplicationState::getVars();
        $customPost = $vars['routing-state']['queried-object'];
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
        /**
         * Prettyprint the code
         */
        $content .= '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
        return $content;
    }
}
