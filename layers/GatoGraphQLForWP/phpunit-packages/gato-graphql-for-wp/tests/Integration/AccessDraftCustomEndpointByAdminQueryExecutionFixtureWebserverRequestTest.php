<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessDraftCustomEndpointByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessDraftCustomEndpointQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-draft-custom-endpoint-by-admin';
    }
}
