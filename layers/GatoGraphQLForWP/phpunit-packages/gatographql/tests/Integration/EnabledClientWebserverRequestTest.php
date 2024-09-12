<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEnabledClientWebserverRequestTestCase;

/**
 * Test that enabling clients (GraphiQL/Voyager) works well
 */
class EnabledClientWebserverRequestTest extends AbstractEnabledClientWebserverRequestTestCase
{
    /**
     * @return array<string,string[]>
     */
    public static function provideEnabledClientEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                'graphiql/',
            ],
            'single-endpoint-voyager' => [
                'schema/',
            ],
        ];
    }
}
