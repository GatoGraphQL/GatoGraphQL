<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractResponseHeaderWebserverRequestTestCase;

class ResponseHeaderWebserverRequestTest extends AbstractResponseHeaderWebserverRequestTestCase
{
    use ResponseHeaderWebserverRequestTestTrait;

    /**
     * @return array<string,string[]>
     */
    public static function provideResponseHeaderEntries(): array
    {
        return [
            'single-endpoint' => [
                'graphql/?query={ id }',
                '',
            ],
        ];
    }
}
