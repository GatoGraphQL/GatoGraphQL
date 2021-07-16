<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\EndpointResolvers\EndpointResolverTrait;

abstract class AbstractGraphQLQueryResolutionEndpointExecuter extends AbstractEndpointExecuter
{
    use EndpointResolverTrait;
    
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        // Check we're resolving the GraphQL query
        if (!$this->isGraphQLQueryExecution()) {
            return false;
        }

        // Check we're loading the corresponding CPT
        $customPostType = $this->getCustomPostType();
        if (!\is_singular($customPostType->getCustomPostType())) {
            return false;
        }

        // Check the endpoint is not disabled
        global $post;
        if (!$customPostType->isEndpointEnabled($post)) {
            return false;
        }

        return true;
    }

    abstract protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType;

    /**
     * Indicates if we executing the GraphQL query.
     * To find out how, keep it simple: Passing ?view=... is for some other purpose,
     * so just check that URL arg "view" is not set to any value.
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return !isset($_REQUEST[RequestParams::VIEW]) || !$_REQUEST[RequestParams::VIEW];
    }

    public function executeEndpoint(): void
    {
        $this->executeGraphQLQuery();
    }
}
