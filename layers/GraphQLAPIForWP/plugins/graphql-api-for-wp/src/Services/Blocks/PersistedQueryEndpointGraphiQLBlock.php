<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;

/**
 * GraphiQL block for Persisted Queries
 */
class PersistedQueryEndpointGraphiQLBlock extends AbstractGraphiQLBlock implements PersistedQueryEndpointEditorBlockServiceTagInterface
{
    public function getBlockPriority(): int
    {
        return 200;
    }

    /**
     * If we are editing a Persisted Query, pass its ID to the endpoint,
     * so it can set-up the Schema Configuration for the schema
     */
    protected function getAdminGraphQLEndpoint(): string
    {
        /** @var EditorHelpers */
        $editorHelpers = $this->instanceManager->getInstance(EditorHelpers::class);
        if ($persistedQueryEndpointCustomPostID = $editorHelpers->getEditingPostID()) {
            return $this->endpointHelpers->getAdminPersistedQueryGraphQLEndpoint(
                $persistedQueryEndpointCustomPostID,
                true
            );
        }
        return $this->endpointHelpers->getAdminConfigurableSchemaGraphQLEndpoint(true);
    }
}
