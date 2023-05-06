<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

/**
 * Test that, if the user is not authenticated, the GraphQL endpoints
 * cannot be accessed.
 */
class WordPressNonAuthenticatedUserWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $query = $this->getGraphQLQuery();
        $entries = [];
        foreach (WordPressAuthenticatedUserEndpoints::ENDPOINTS as $dataName => $endpoint) {
            $entries[$dataName] = [
                'text/html; charset=UTF-8',
                null,
                $endpoint,
                [],
                $query,
            ];
        }
        return $entries;
    }

    protected function getGraphQLQuery(): string
    {
        return <<<GRAPHQL
            {
                id
            }       
        GRAPHQL;
    }
}
