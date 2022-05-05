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
}
