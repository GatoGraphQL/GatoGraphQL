<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait DisableSchemaModulesOnPrivateEndpointHasChangeFixtureEndpointWebserverRequestTestTrait
{
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-has-change';
    }
}
