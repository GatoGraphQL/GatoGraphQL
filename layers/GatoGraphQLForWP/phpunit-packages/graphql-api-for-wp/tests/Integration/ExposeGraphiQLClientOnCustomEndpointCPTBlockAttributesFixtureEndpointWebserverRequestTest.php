<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;

class ExposeGraphiQLClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractExposeClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_CUSTOM_ENDPOINT_ID = 196;

    protected function getEndpoint(): string
    {
        return 'graphql/website/';
    }

    protected function getClientName(): string
    {
        return RequestParams::VIEW_GRAPHIQL;
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_CUSTOM_ENDPOINT_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'gato-graphql/endpoint-graphiql';
    }
}
