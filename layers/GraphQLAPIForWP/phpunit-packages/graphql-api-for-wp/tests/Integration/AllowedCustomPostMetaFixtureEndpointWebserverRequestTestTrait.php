<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait AllowedCustomPostMetaFixtureEndpointWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-allowed-custompost-meta';
    }

    /**
     * @return string[]
     */
    protected function getAllowedCustomPostMetaKeyEntriesNewValue(): array
    {
        $allowedEntries = [
            '_edit_last',
            '/_edit_.*/',
            '#_edit_([a-zA-Z]*)#',
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
