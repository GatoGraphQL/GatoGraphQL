<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AdminClientQueryExecutionFixtureWebserverRequestTest extends AbstractAdminClientQueryExecutionFixtureWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-introspection-and-config';
    }

    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-client';
    }
}
