<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait SchemaPayloadTypesForMutationsWithObjectFieldsFixtureEndpointWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-payload-types-for-mutations-with-object-fields';
    }
}
