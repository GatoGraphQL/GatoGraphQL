<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\ModifyCPTBlockAttributesWebserverRequestTestCaseTrait;

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

    protected function executeCPTBlockAttributesSetUpTearDown(string $dataName): bool
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
