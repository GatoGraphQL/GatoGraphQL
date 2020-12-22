<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\General\EditorHelpers;
use GraphQLAPI\GraphQLAPI\General\RequestParams;

/**
 * GraphiQL block for Persisted Queries
 */
class PersistedQueryGraphiQLBlock extends AbstractGraphiQLBlock
{
    /**
     * If we are editing a Persisted Query, pass its ID to the endpoint,
     * so it can set-up the Schema Configuration for the schema
     */
    protected function getAdminGraphQLEndpoint(): string
    {
        $endpoint = parent::getAdminGraphQLEndpoint();
        if ($persistedQueryID = EditorHelpers::getEditingPostID()) {
            $endpoint = \add_query_arg(
                RequestParams::PERSISTED_QUERY_ID,
                $persistedQueryID,
                $endpoint
            );
        }
        return $endpoint;
    }
}
