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
    protected GraphQLAccessControlListCustomPostType $graphQLAccessControlListCustomPostType;
    protected GraphQLCacheControlListCustomPostType $graphQLCacheControlListCustomPostType;
    protected GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType;

    #[Required]
    public function autowireListOfCPTEntitiesRootObjectTypeFieldResolver(
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
            'accessControlLists' => $this->graphQLAccessControlListCustomPostType->getCustomPostType(),
            'cacheControlLists' => $this->graphQLCacheControlListCustomPostType->getCustomPostType(),
            'schemaConfigurations' => $this->graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            default => '', // It will never reach here
        };
    }
}
