<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\ModifyCPTBlockAttributesWebserverRequestTestCaseTrait;

abstract class AbstractModifyCPTBlockAttributesFixtureEndpointWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use ModifyCPTBlockAttributesWebserverRequestTestCaseTrait;
    use ModifyValueFixtureEndpointWebserverRequestTestCaseTrait;

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

    protected function executeCPTBlockAttributesSetUpTearDown(string|int $dataName): bool
    {
        return $this->executeSetUpTearDownUnlessIsOriginalTestCase($dataName);
    }

    /**
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        return $this->reorderProviderEndpointEntriesToExecuteOriginalTestFirst($providerItems);
    }
}
