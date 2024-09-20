<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use function str_ends_with;

trait OKSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait
{
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-editing-access-ok';
    }

    protected function is500Exception(): bool
    {
        $dataName = $this->getDataName();
        return str_ends_with($dataName, ':0');
    }

    abstract protected function getDataName(): string;
}
