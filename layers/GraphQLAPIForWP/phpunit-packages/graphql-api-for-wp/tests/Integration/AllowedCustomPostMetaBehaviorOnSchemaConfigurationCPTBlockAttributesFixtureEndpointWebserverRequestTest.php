<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class AllowedCustomPostMetaBehaviorOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest extends AllowedCustomPostMetaOnSchemaConfigurationCPTBlockAttributesFixtureEndpointWebserverRequestTest
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-allowed-custompost-meta-behavior';
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
