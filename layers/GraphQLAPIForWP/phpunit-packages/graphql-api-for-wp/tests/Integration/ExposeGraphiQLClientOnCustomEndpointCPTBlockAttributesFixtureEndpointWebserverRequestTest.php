<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractRequestClientCPTBlockAttributesWebserverRequestTest;

class ExposeGraphiQLClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractRequestClientCPTBlockAttributesWebserverRequestTest
{
    public const WEBSITE_CUSTOM_ENDPOINT_ID = 196;

    // protected function getEndpoint(): string
    // {
    //     /**
    //      * This endpoint:
    //      *
    //      * - Originally has the Schema Configuration "Website" (with ID 191)
    //      * - Then changed to "Power users" (with ID 261)
    //      */
    //     return 'graphql/website/';
    // }

    // protected function getFixtureFolder(): string
    // {
    //     return __DIR__ . '/fixture-schema-config-on-custom-endpoint-in-cpt';
    // }

    protected function getClientURL(): string
    {
        return 'graphql/website/?view=graphiql';
    }

    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            BlockAttributeNames::IS_ENABLED => false,
        ];
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_CUSTOM_ENDPOINT_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'graphql-api/endpoint-graphiql';
    }
}
