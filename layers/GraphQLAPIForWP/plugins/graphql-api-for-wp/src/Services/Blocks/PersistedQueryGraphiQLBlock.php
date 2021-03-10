<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

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
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EditorHelpers */
        $editorHelpers = $instanceManager->getInstance(EditorHelpers::class);
        if ($persistedQueryID = $editorHelpers->getEditingPostID()) {
            $endpoint = \add_query_arg(
                RequestParams::PERSISTED_QUERY_ID,
                $persistedQueryID,
                $endpoint
            );
        }
        return $endpoint;
    }
}
