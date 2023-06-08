<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;
use RuntimeException;

abstract class AbstractPersistedQueryFixtureWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureTestCaseTrait;

    /**
     * Since PHPUnit v10, this is not possible anymore!
     */
    // final public function dataSetAsString(): string
    // {
    //     return $this->addFixtureFolderInfo(parent::dataSetAsString());
    // }

    /**
     * Retrieve all files under the "/fixture" folder
     * to retrieve the expected response bodies, as JSON.
     *
     * Additional properties (such as the params)
     * must be provided via code.
     *
     * @return array<string,array<string|array<string,mixed>>>
     */
    final public static function provideEndpointEntries(): array
    {
        $fixtureFolder = static::getFixtureFolder();
        $bodyResponseFileNameFileInfos = static::findFilesInDirectory(
            $fixtureFolder,
            ['*.json'],
            ['*.disabled.json', '*.var.json'],
        );

        $providerItems = [];
        foreach ($bodyResponseFileNameFileInfos as $bodyResponseFileInfo) {
            $fileName = $bodyResponseFileInfo->getFilenameWithoutExtension();
            $dataName = $fileName;
            $providerItems[$dataName] = [
                'application/json',
                $bodyResponseFileInfo->getContents(),
                static::getEndpoint($dataName),
                static::getParams($dataName),
                static::getQuery($dataName),
                static::getVariables($dataName),
                static::getOperationName($dataName),
                static::getEntryMethod($dataName),
            ];
        }
        return $providerItems;
    }

    protected static function getEntryMethod(string $dataName): string
    {
        return 'POST';
    }

    /**
     * @throws RuntimeException If the endpoint is not defined
     */
    protected static function getEndpoint(string $dataName): string
    {
        throw new RuntimeException(
            sprintf(
                'The endpoint for dataName "%s" has not been defined',
                $dataName
            )
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getParams(string $dataName): array
    {
        return [];
    }

    protected static function getQuery(string $dataName): string
    {
        return '';
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getVariables(string $dataName): array
    {
        return [];
    }

    protected static function getOperationName(string $dataName): string
    {
        return '';
    }
}
