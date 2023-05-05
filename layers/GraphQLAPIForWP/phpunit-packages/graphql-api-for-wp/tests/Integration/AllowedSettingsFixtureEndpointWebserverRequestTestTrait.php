<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait AllowedSettingsFixtureEndpointWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-allowed-settings';
    }

    /**
     * @return string[]
     */
    protected function getAllowedSettingsKeyEntriesNewValue(): array
    {
        $allowedEntries = [
            'mailserver_url',
            '/mailserver_.*/',
            '#mailserver_([a-zA-Z]*)#',
        ];
        $value = [
            'something',
        ];

        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            $value[] = $allowedEntries[0];
        } elseif (str_ends_with($dataName, ':2')) {
            $value[] = $allowedEntries[1];
        } elseif (str_ends_with($dataName, ':3')) {
            $value[] = $allowedEntries[2];
        }

        return $value;
    }

    abstract protected function getDataName(): string;
}
