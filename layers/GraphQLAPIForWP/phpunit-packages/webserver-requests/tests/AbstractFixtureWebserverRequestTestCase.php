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
    protected function provideEndpoints(string $endpoint): array
    {

    }

    /**
     * Directory under the fixture files are placed
     */
    abstract protected function getFixtureFolder(): string;
    
    abstract protected function provideFixtureEndpoints(string $endpoint): array;
}
