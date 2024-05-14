<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class ApplicationPasswordQueryExecutionFixtureWebserverRequestTest extends AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase
{
    use ApplicationPasswordQueryExecutionFixtureWebserverRequestTestTrait;

    protected static function getEndpoint(): string
    {
        return 'graphql/';
    }
}
