<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SchemaMutationsFixtureEndpointWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-mutations';
    }

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    /**
     * Because the slug and url can change if the same file
     * has been uploaded multiple times, remove the counter
     * number from the file name.
     */
    protected function adaptResponseBody(string $responseBody): string
    {
        /** @var string */
        $responseBody = preg_replace(
            [
                '/("slug"\: ?)"([a-z-]*)(-[0-9]*)(-.*)?"/',
                '/("src"\: ?)"(.*)(-[0-9]*)(\.[a-z]*)?"/',
            ],
            [
                '$1"$2$4"',
                '$1"$2$4"',
            ],
            $responseBody
        );
        return $responseBody;
    }
}
