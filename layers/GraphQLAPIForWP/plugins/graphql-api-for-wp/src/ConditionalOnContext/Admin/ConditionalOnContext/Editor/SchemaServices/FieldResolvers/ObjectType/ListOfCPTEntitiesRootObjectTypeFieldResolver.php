<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
class ListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractListOfCPTEntitiesRootObjectTypeFieldResolver
{
    private ?GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType = null;
    private ?GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType = null;
    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final public function setGraphQLAccessControlListCustomPostType(GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType): void
    {
        $this->graphQLAccessControlListCustomPostType = $graphQLAccessControlListCustomPostType;
    }
    final protected function getGraphQLAccessControlListCustomPostType(): GraphQLAccessControlListCustomPostType
    {
        return $this->graphQLAccessControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
    }
    final public function setGraphQLCacheControlListCustomPostType(GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType): void
    {
        $this->graphQLCacheControlListCustomPostType = $graphQLCacheControlListCustomPostType;
    }
    final protected function getGraphQLCacheControlListCustomPostType(): GraphQLCacheControlListCustomPostType
    {
        return $this->graphQLCacheControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
    }
    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        return $this->graphQLSchemaConfigurationCustomPostType ??= $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'accessControlLists',
            'cacheControlLists',
            'schemaConfigurations',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'accessControlLists' => $this->__('Access Control Lists', 'graphql-api'),
            'cacheControlLists' => $this->__('Cache Control Lists', 'graphql-api'),
            'schemaConfigurations' => $this->__('Schema Configurations', 'graphql-api'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldCustomPostType(string $fieldName): string
    {
        return match ($fieldName) {
            'accessControlLists' => $this->getGraphQLAccessControlListCustomPostType()->getCustomPostType(),
            'cacheControlLists' => $this->getGraphQLCacheControlListCustomPostType()->getCustomPostType(),
            'schemaConfigurations' => $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType(),
            default => '', // It will never reach here
        };
    }
}
