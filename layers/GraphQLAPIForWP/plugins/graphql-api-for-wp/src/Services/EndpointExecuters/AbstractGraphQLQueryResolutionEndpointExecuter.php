<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;

abstract class AbstractGraphQLQueryResolutionEndpointExecuter extends AbstractEndpointExecuter
{
    public function isServiceEnabled(): bool
    {
        // Check we're loading the corresponding CPT
        if (!\is_singular($this->getCustomPostType()->getCustomPostType())) {
            return false;
        }

        // Check we're resolving the GraphQL query
        if (!$this->isGraphQLQueryExecution()) {
            return false;
        }
        return parent::isServiceEnabled();
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
}
