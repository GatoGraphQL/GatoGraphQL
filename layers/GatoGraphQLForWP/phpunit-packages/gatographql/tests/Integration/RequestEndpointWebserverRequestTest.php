<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class RequestEndpointWebserverRequestTest extends AbstractRequestEndpointWebserverRequestTest
{
    /**
     * @return array<string,string>
     */
    protected static function getEndpointNamePaths(): array
    {
        return [
            'single-endpoint' => 'graphql/',
            'custom-endpoint' => 'graphql/mobile-app/',
            'custom-endpoint-with-api-hierarchy' => 'graphql/customers/penguin-books/',
        ];
    }
}
