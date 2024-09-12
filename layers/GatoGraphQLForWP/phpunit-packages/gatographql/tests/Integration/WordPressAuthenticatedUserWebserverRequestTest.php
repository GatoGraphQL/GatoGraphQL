<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that the authenticated user can access the GraphQL endpoints.
 */
class WordPressAuthenticatedUserWebserverRequestTest extends AbstractWordPressAuthenticatedUserWebserverRequestTest
{
    /**
     * @return array<string,string>
     */
    protected static function getWordPressAuthenticatedUserEndpoints(): array
    {
        return WordPressAuthenticatedUserEndpoints::ENDPOINTS;
    }
}
