<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLFieldDeprecationListCustomPostType;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * FieldResolver for the Custom Post Types from this plugin
 */
class CPTFieldResolver extends AbstractQueryableFieldResolver
{
    /**
     * Option to tell the hook to not remove the private CPTs when querying
     */
    public const QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS = 'allow-querying-private-cpts';

    /**
     * @return string[]
     */
    public function getClassesToAttachTo(): array
    {
        return [
            RootTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'accessControlLists',
            'cacheControlLists',
            'fieldDeprecationLists',
            'schemaConfigurations',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $ret = match($fieldName) {
            'accessControlLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'cacheControlLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'fieldDeprecationLists' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'schemaConfigurations' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            default => parent::getSchemaFieldType($typeResolver, $fieldName),
        };
        return $ret;
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $ret = match($fieldName) {
            'accessControlLists' => $this->translationAPI->__('Access Control Lists', 'graphql-api'),
            'cacheControlLists' => $this->translationAPI->__('Cache Control Lists', 'graphql-api'),
            'fieldDeprecationLists' => $this->translationAPI->__('Field Deprecation Lists', 'graphql-api'),
            'schemaConfigurations' => $this->translationAPI->__('Schema Configurations', 'graphql-api'),
            default => parent::getSchemaFieldDescription($typeResolver, $fieldName),
        };
        return $ret;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                $schemaFieldArgs = array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
                // Remove the "customPostTypes" field argument
                $schemaFieldArgs = array_filter(
                    $schemaFieldArgs,
                    fn ($schemaFieldArg) => $schemaFieldArg[SchemaDefinition::ARGNAME_NAME] != 'customPostTypes'
                );
                break;
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                $query = [
                    'limit' => -1,
                    'status' => [
                        Status::PUBLISHED,
                    ],
                ];
                return $query;
        }
        return [];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                // Remove the "customPostTypes" field argument
                unset($fieldArgs['customPostTypes']);
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                // Execute for the corresponding field name
                $customPostTypes = [
                    'accessControlLists' => GraphQLAccessControlListCustomPostType::CUSTOM_POST_TYPE,
                    'cacheControlLists' => GraphQLCacheControlListCustomPostType::CUSTOM_POST_TYPE,
                    'fieldDeprecationLists' => GraphQLFieldDeprecationListCustomPostType::CUSTOM_POST_TYPE,
                    'schemaConfigurations' => GraphQLSchemaConfigurationCustomPostType::CUSTOM_POST_TYPE,
                ];
                $query['custompost-types'] = [
                    $customPostTypes[$fieldName],
                ];
                $options = [
                    'return-type' => ReturnTypes::IDS,
                    // Do not use the limit set in the settings for custom posts
                    'skip-max-limit' => true,
                    // With this flag, the hook will not remove the private CPTs
                    self::QUERY_OPTION_ALLOW_QUERYING_PRIVATE_CPTS => true,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPosts($query, $options);
        }

        return parent::resolveValue(
            $typeResolver,
            $resultItem,
            $fieldName,
            $fieldArgs,
            $variables,
            $expressions,
            $options
        );
    }

    public function resolveFieldTypeResolverClass(
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): ?string {
        switch ($fieldName) {
            case 'accessControlLists':
            case 'cacheControlLists':
            case 'fieldDeprecationLists':
            case 'schemaConfigurations':
                return CustomPostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass(
            $typeResolver,
            $fieldName,
        );
    }
}
