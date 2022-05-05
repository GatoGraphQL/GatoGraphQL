<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\ModifyPluginSettingsWebserverRequestTestCaseTrait;

abstract class AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use ModifyPluginSettingsWebserverRequestTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modifyPluginSettingsSetUp();
    }

    protected function tearDown(): void
    {
        $this->modifyPluginSettingsTearDown();

        parent::tearDown();
    }

    /**
     * Use the ending ":0" to denote the "before" test, i.e.
     * testing that the current value in the DB produces a certain
     * result
     */
    protected function executePluginSettingsSetUpTearDown(string $dataName): bool
    {
        return !str_ends_with($dataName, ':0');
    }
}
