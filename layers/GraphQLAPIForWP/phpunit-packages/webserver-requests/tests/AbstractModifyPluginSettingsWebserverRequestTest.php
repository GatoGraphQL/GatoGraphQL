<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

abstract class AbstractModifyPluginSettingsWebserverRequestTest extends AbstractWebserverRequestTestCase
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
