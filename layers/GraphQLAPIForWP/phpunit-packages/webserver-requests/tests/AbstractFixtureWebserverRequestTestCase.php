<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

abstract class AbstractFixtureWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
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
        $entries = [];
        foreach ($this->getFixtureEndpointEntries() as $fixtureEntry) {
            $dataName = '{fileName}';
            $expectedResponseBody = '';
            $expectedContentType = true ? 'application/json' : 'text/html';
            $entries[] = [
                $expectedResponseBody,
                $endpoint = null,
                $this->getParams($dataName),
                $this->getBody($dataName),
                $expectedContentType,
                $this->getEntryMethod($dataName),
            ];
        }
        return $entries;
    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;
    
    abstract protected function getFixtureEndpointEntries(): array;

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
