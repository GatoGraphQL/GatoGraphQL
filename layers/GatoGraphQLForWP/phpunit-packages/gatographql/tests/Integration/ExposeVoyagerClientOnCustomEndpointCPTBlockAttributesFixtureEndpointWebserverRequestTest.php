<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;

class ExposeVoyagerClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AbstractExposeClientOnCustomEndpointCPTBlockAttributesFixtureEndpointWebserverRequestTestCase
{
    public const WEBSITE_CUSTOM_ENDPOINT_ID = 196;

    protected static function getEndpoint(): string
    {
        return 'graphql/website/';
    }

    protected function getClientName(): string
    {
        return RequestParams::VIEW_SCHEMA;
    }

    protected function getCustomPostID(string $dataName): int
    {
        return self::WEBSITE_CUSTOM_ENDPOINT_ID;
    }

    protected function getBlockNamespacedID(string $dataName): string
    {
        return 'gatographql-pro/endpoint-voyager';
    }
}
