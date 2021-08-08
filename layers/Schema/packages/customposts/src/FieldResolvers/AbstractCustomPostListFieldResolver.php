<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInnerModuleProcessor;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractCustomPostListFieldResolver extends AbstractQueryableFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'customPosts',
            'customPostCount',
            'unrestrictedCustomPosts',
            'unrestrictedCustomPostCount',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'unrestrictedCustomPosts',
            'unrestrictedCustomPostCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'customPosts' => SchemaDefinition::TYPE_ID,
            'customPostCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedCustomPosts' => SchemaDefinition::TYPE_ID,
            'unrestrictedCustomPostCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'customPostCount',
            'unrestrictedCustomPostCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'customPosts',
            'unrestrictedCustomPosts'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'customPosts' => $this->translationAPI->__('Custom posts', 'pop-posts'),
            'customPostCount' => $this->translationAPI->__('Number of custom posts', 'pop-posts'),
            'unrestrictedCustomPosts' => $this->translationAPI->__('[Unrestricted] Custom posts', 'pop-posts'),
            'unrestrictedCustomPostCount' => $this->translationAPI->__('[Unrestricted] Number of custom posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
            case 'unrestrictedCustomPosts':
            case 'unrestrictedCustomPostCount':
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
            case 'customPosts':
            case 'customPostCount':
            case 'unrestrictedCustomPosts':
            case 'unrestrictedCustomPostCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'customPosts' => [
                CustomPostFilterInnerModuleProcessor::class,
                CustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST
            ],
            'unrestrictedCustomPosts' => [
                CustomPostFilterInnerModuleProcessor::class,
                CustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTLIST
            ],
            'customPostCount' => [
                CustomPostFilterInnerModuleProcessor::class,
                CustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT
            ],
            'unrestrictedCustomPostCount' => [
                CustomPostFilterInnerModuleProcessor::class,
                CustomPostFilterInnerModuleProcessor::MODULE_FILTERINNER_ADMINUNIONCUSTOMPOSTCOUNT
            ],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        };
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
        $sharedQuery = [
            'types-from-union-resolver-class' => CustomPostUnionTypeResolver::class,
            'status' => [
                Status::PUBLISHED,
            ],
        ];
        switch ($fieldName) {
            case 'customPosts':
            case 'unrestrictedCustomPosts':
                return array_merge(
                    $sharedQuery,
                    [
                        'limit' => ComponentConfiguration::getCustomPostListDefaultLimit(),
                    ]
                );
            case 'customPostCount':
            case 'unrestrictedCustomPostCount':
                return $sharedQuery;
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
            case 'customPosts':
            case 'unrestrictedCustomPosts':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPosts($query, $options);
            case 'customPostCount':
            case 'unrestrictedCustomPostCount':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPostCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'customPosts':
            case 'unrestrictedCustomPosts':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
