<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;

class ExposeVoyagerClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractExposeClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest
{
    public const WEBSITE_CUSTOM_ENDPOINT_ID = 196;

    protected function getEndpoint(): string
    {
        return 'graphql/website/';
    }

    protected function getClientName(): string
    {
        return RequestParams::VIEW_SCHEMA;
    }

    protected function getCustomPostID(string|int $dataName): int
    {
        return self::WEBSITE_CUSTOM_ENDPOINT_ID;
    }

    protected function getBlockNamespacedID(string|int $dataName): string
    {
        return 'graphql-api/endpoint-voyager';
    }
}
