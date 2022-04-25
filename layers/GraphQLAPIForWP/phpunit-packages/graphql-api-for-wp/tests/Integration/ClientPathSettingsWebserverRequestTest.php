<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractClientPathSettingsWebserverRequestTest;

/**
 * Test that updating the path for a client (GraphiQL/Voyager) works well
 */
class ClientPathSettingsWebserverRequestTest extends AbstractClientPathSettingsWebserverRequestTest
{
    use SingleEndpointClientWebserverRequestTestCaseTrait;

    /**
     * @return array<string,string[]> Array of 2 elements: [ ${newClientPath}, ${previousClientPath} ]
     */
    protected function provideClientPathEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                '/new-graphiql/',
                '/graphiql/',
            ],
            'single-endpoint-voyager' => [
                '/new-schema/',
                '/schema/',
            ],
        ];
    }

    protected function getModuleID(string $dataName): string
    {
        return $this->getSingleEndpointClientModuleID($dataName);
    }
}
