<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * FieldResolver for the Custom Post Types from this plugin
 */
class ListOfCPTEntitiesRootFieldResolver extends AbstractListOfCPTEntitiesRootFieldResolver
{
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

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'accessControlLists' => $this->translationAPI->__('Access Control Lists', 'graphql-api'),
            'cacheControlLists' => $this->translationAPI->__('Cache Control Lists', 'graphql-api'),
            'schemaConfigurations' => $this->translationAPI->__('Schema Configurations', 'graphql-api'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldCustomPostType(string $fieldName): string
    {
        /** @var GraphQLAccessControlListCustomPostType */
        $accessControlListCustomPostTypeService = $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
        /** @var GraphQLCacheControlListCustomPostType */
        $cacheControlListCustomPostTypeService = $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $schemaConfigurationCustomPostTypeService = $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
        return match ($fieldName) {
            'accessControlLists' => $accessControlListCustomPostTypeService->getCustomPostType(),
            'cacheControlLists' => $cacheControlListCustomPostTypeService->getCustomPostType(),
            'schemaConfigurations' => $schemaConfigurationCustomPostTypeService->getCustomPostType(),
            default => '', // It will never reach here
        };
    }
}
