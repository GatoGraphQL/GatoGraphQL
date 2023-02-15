<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigSchemaAllowAccessToEntriesBlock;
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
            AbstractSchemaConfigSchemaAllowAccessToEntriesBlock::ATTRIBUTE_NAME_BEHAVIOR => $this->getBehaviorNewValue(),
        ];
    }

    protected function getBehaviorNewValue(): string
    {
        return Behaviors::ALLOW;
    }
}
