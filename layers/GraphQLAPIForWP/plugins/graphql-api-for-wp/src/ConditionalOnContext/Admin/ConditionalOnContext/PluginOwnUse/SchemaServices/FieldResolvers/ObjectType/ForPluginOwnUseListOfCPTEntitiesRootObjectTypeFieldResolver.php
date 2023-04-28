<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\PluginOwnUse\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
class ForPluginOwnUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractForPluginOwnUseListOfCPTEntitiesRootObjectTypeFieldResolver
{
    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        /** @var GraphQLSchemaConfigurationCustomPostType */
        return $this->graphQLSchemaConfigurationCustomPostType ??= $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'schemaConfigurations',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'schemaConfigurations' => $this->__('Schema Configurations', 'graphql-api'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldCustomPostType(FieldDataAccessorInterface $fieldDataAccessor): string
    {
        return match ($fieldDataAccessor->getFieldName()) {
            'schemaConfigurations' => $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType(),
            default => '', // It will never reach here
        };
    }
}
