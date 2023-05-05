<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class AllowedUserMetaBehaviorOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AllowedUserMetaOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-allowed-user-meta-behavior';
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCPTBlockAttributesNewValue(): array
    {
        return [
            ...parent::getCPTBlockAttributesNewValue(),
            BlockAttributeNames::BEHAVIOR => $this->getBehaviorNewValue(),
        ];
    }

    protected function getBehaviorNewValue(): string
    {
        return Behaviors::ALLOW;
    }
}
