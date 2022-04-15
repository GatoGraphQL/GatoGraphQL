<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Standalone\FixtureTestCaseTrait;

abstract class AbstractFixtureWebserverRequestTestCase extends AbstractWebserverRequestTestCase
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
    protected function provideEndpointEntries(): array
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
                $bodyResponseFileInfo->getContents(),
                $this->getEndpoint($dataName),
                $this->getParams($dataName),
                $this->getBody($dataName),
                str_ends_with($bodyResponseFile, '.html') ? 'text/html' : 'application/json',
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

    protected function getEndpoint(string $dataName): string
    {
        return '';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getParams(string $dataName): array
    {
        return [];
    }

    protected function getBody(string $dataName): string
    {
        return '';
    }

    // protected function getExpectedContentType(string $dataName): string
    // {
    //     return 'application/json';
    // }
}
