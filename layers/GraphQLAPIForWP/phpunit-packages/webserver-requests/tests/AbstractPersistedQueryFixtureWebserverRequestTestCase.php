<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Standalone\FixtureTestCaseTrait;
use RuntimeException;

abstract class AbstractPersistedQueryFixtureWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use FixtureTestCaseTrait;

    /**
     * Retrieve all files under the "/fixture" folder
     * to retrieve the expected response bodies.
     *
     * Depending on the file extension, we expect:
     *
     * - .json => application/json
     * - .html => text/html
     *
     * Additional properties (such as the params)
     * must be provided via code.
     */
    final protected function provideEndpointEntries(): array
    {
        $fixtureFolder = $this->getFixtureFolder();
        $bodyResponseFileNameFileInfos = $this->findFilesInDirectory(
            $fixtureFolder,
            ['*.json'],
            ['*.disabled.json']
        );

        $providerItems = [];
        foreach ($bodyResponseFileNameFileInfos as $bodyResponseFileInfo) {
            /** @var string */
            $bodyResponseFile = $bodyResponseFileInfo->getRealPath();
            $fileName = $bodyResponseFileInfo->getFilenameWithoutExtension();
            $dataName = $fileName;
            $providerItems[$dataName] = [
                str_ends_with($bodyResponseFile, '.html') ? 'text/html' : 'application/json',
                $bodyResponseFileInfo->getContents(),
                $this->getEndpoint($dataName),
                $this->getParams($dataName),
                $this->getQuery($dataName),
                $this->getVariables($dataName),
                $this->getEntryMethod($dataName),
            ];
        }
        return $providerItems;
    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;

    protected function getEntryMethod(string $dataName): string
    {
        return $this->getMethod();
    }

    /**
     * @throws RuntimeException If the endpoint is not defined
     */
    protected function getEndpoint(string $dataName): string
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
    protected function getParams(string $dataName): array
    {
        return [];
    }

    protected function getQuery(string $dataName): string
    {
        return '';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getVariables(string $dataName): array
    {
        return [];
    }
}
