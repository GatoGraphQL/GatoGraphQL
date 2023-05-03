<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

abstract class AbstractModifyCPTBlockAttributesWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use ModifyCPTBlockAttributesWebserverRequestTestCaseTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modifyCPTBlockAttributesSetUp();
    }

    protected function tearDown(): void
    {
        $this->modifyCPTBlockAttributesTearDown();

        parent::tearDown();
    }
}
