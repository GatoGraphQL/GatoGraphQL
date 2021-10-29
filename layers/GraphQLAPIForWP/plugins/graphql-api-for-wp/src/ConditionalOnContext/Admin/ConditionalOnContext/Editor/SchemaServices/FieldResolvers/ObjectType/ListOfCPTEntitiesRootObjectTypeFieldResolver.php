<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
class ListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractListOfCPTEntitiesRootObjectTypeFieldResolver
{
    private ?GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType = null;
    private ?GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType = null;
    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    public function setGraphQLAccessControlListCustomPostType(GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType): void
    {
        $this->graphQLAccessControlListCustomPostType = $graphQLAccessControlListCustomPostType;
    }
    protected function getGraphQLAccessControlListCustomPostType(): GraphQLAccessControlListCustomPostType
    {
        return $this->graphQLAccessControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
    }
    public function setGraphQLCacheControlListCustomPostType(GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType): void
    {
        $this->graphQLCacheControlListCustomPostType = $graphQLCacheControlListCustomPostType;
    }
    protected function getGraphQLCacheControlListCustomPostType(): GraphQLCacheControlListCustomPostType
    {
        return $this->graphQLCacheControlListCustomPostType ??= $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
    }
    public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        return $this->graphQLSchemaConfigurationCustomPostType ??= $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
    }

    //#[Required]
    final public function autowireListOfCPTEntitiesRootObjectTypeFieldResolver(
        GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType,
        GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType,
        GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType,
    ): void {
        $this->graphQLAccessControlListCustomPostType = $graphQLAccessControlListCustomPostType;
        $this->graphQLCacheControlListCustomPostType = $graphQLCacheControlListCustomPostType;
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
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
            'accessControlLists' => $this->translationAPI->__('Access Control Lists', 'graphql-api'),
            'cacheControlLists' => $this->translationAPI->__('Cache Control Lists', 'graphql-api'),
            'schemaConfigurations' => $this->translationAPI->__('Schema Configurations', 'graphql-api'),
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
