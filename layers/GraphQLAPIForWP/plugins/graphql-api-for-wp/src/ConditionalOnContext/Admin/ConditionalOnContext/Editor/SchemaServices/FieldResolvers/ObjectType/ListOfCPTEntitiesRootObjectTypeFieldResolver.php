<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

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
    ) {
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
        return match ($fieldName) {
            'accessControlLists' => $this->graphQLAccessControlListCustomPostType->getCustomPostType(),
            'cacheControlLists' => $this->graphQLCacheControlListCustomPostType->getCustomPostType(),
            'schemaConfigurations' => $this->graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            default => '', // It will never reach here
        };
    }
}
