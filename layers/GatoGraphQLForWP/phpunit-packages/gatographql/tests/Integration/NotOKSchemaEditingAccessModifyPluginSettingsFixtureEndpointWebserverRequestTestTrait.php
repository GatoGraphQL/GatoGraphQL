<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait NotOKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait
{
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-editing-access-not-ok';
    }

    protected function is500Exception(): bool
    {
        return true;
    }
}
