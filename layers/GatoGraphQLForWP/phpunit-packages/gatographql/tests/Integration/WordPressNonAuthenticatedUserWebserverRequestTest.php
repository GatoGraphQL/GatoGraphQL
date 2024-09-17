<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * Test that, if the user is not authenticated, the GraphQL endpoints
 * cannot be accessed.
 */
class WordPressNonAuthenticatedUserWebserverRequestTest extends AbstractWordPressNonAuthenticatedUserWebserverRequestTestCase
{
    /**
     * @return array<string,string>
     */
    protected static function getWordPressAuthenticatedUserEndpoints(): array
    {
        return WordPressAuthenticatedUserEndpoints::ENDPOINTS;
    }
}
