<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

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
        if ($persistedQueryEndpointCustomPostID = $this->getEditorHelpers()->getEditingPostID()) {
            return $this->getEndpointHelpers()->getAdminPersistedQueryGraphQLEndpoint(
                $persistedQueryEndpointCustomPostID,
                true
            );
        }
        return $this->getEndpointHelpers()->getAdminGraphQLEndpoint(true);
    }
}
