<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\ConditionalOnContext\PluginOwnUse\SchemaServices\FieldResolvers\ObjectType;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
class ForPluginOwnUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractForPluginOwnUseListOfCPTEntitiesRootObjectTypeFieldResolver
{
    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;
    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        if ($this->graphQLSchemaConfigurationCustomPostType === null) {
            /** @var GraphQLSchemaConfigurationCustomPostType */
            $graphQLSchemaConfigurationCustomPostType = $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
            $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
        }
        return $this->graphQLSchemaConfigurationCustomPostType;
    }
    final public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        if ($this->graphQLPersistedQueryEndpointCustomPostType === null) {
            /** @var GraphQLPersistedQueryEndpointCustomPostType */
            $graphQLPersistedQueryEndpointCustomPostType = $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
            $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
        }
        return $this->graphQLPersistedQueryEndpointCustomPostType;
    }
    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        if ($this->graphQLCustomEndpointCustomPostType === null) {
            /** @var GraphQLCustomEndpointCustomPostType */
            $graphQLCustomEndpointCustomPostType = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
            $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
        }
        return $this->graphQLCustomEndpointCustomPostType;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'schemaConfigurations',
            'persistedQueryEndpoints',
            'customEndpoints',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'schemaConfigurations' => $this->__('Schema Configurations', 'gatographql'),
            'persistedQueryEndpoints' => $this->__('Persisted Query Endpoints', 'gatographql'),
            'customEndpoints' => $this->__('Custom Endpoints', 'gatographql'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldCustomPostType(FieldDataAccessorInterface $fieldDataAccessor): string
    {
        return match ($fieldDataAccessor->getFieldName()) {
            'schemaConfigurations' => $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType(),
            'persistedQueryEndpoints' => $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType(),
            'customEndpoints' => $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType(),
            default => '', // It will never reach here
        };
    }
}
