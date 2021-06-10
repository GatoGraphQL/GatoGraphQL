<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\ModuleProcessors\FieldDataloadModuleProcessor;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractPostFieldResolver extends AbstractQueryableFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'posts',
            'postCount',
            'unrestrictedPosts',
            'unrestrictedPostCount',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'unrestrictedPosts',
            'unrestrictedPostCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'posts' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'postCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedPosts' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'unrestrictedPostCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'posts',
            'postCount',
            'unrestrictedPosts',
            'unrestrictedPostCount',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'posts' => $this->translationAPI->__('Posts', 'pop-posts'),
            'postCount' => $this->translationAPI->__('Number of posts', 'pop-posts'),
            'unrestrictedPosts' => $this->translationAPI->__('[Unrestricted] Posts', 'pop-posts'),
            'unrestrictedPostCount' => $this->translationAPI->__('[Unrestricted] Number of posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
            case 'unrestrictedPosts':
            case 'unrestrictedPostCount':
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'unrestrictedPosts':
            case 'unrestrictedPostCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDefaultFilterDataloadingModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        switch ($fieldName) {
            case 'postCount':
                return [
                    FieldDataloadModuleProcessor::class,
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_POSTCOUNT
                ];
            case 'unrestrictedPosts':
                return [
                    FieldDataloadModuleProcessor::class,
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST
                ];
            case 'unrestrictedPostCount':
                return [
                    FieldDataloadModuleProcessor::class,
                    FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT
                ];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
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
            case 'posts':
            case 'unrestrictedPosts':
                return [
                    'limit' => ComponentConfiguration::getPostListDefaultLimit(),
                    'status' => [
                        Status::PUBLISHED,
                    ],
                ];
            case 'postCount':
            case 'unrestrictedPostCount':
                return [
                    'status' => [
                        Status::PUBLISHED,
                    ],
                ];
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
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'posts':
            case 'unrestrictedPosts':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPosts($query, $options);
            case 'postCount':
            case 'unrestrictedPostCount':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTypeAPI->getPostCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'posts':
            case 'unrestrictedPosts':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
