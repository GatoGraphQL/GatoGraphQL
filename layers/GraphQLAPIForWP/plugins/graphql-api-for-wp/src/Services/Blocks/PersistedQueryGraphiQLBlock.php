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
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EditorHelpers */
        $editorHelpers = $instanceManager->getInstance(EditorHelpers::class);
        if ($persistedQueryCustomPostID = $editorHelpers->getEditingPostID()) {
            return $this->endpointHelpers->getAdminPersistedQueryGraphQLEndpoint(
                $persistedQueryCustomPostID,
                true
            );
        }
        return $this->endpointHelpers->getAdminConfigurableSchemaGraphQLEndpoint(true);
    }
}
