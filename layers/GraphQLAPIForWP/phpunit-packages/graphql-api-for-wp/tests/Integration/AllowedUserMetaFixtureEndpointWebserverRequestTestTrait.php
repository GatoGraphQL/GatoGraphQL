<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

trait AllowedUserMetaFixtureEndpointWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-allowed-user-meta';
    }

    /**
     * @return string[]
     */
    protected function getAllowedUserMetaKeyEntriesNewValue(): array
    {
        $allowedEntries = [
            'last_name',
            '/last_.*/',
            '#last_([a-zA-Z]*)#',
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
