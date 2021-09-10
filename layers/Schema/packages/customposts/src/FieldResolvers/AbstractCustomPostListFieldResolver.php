<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

abstract class AbstractCustomPostListFieldResolver extends AbstractQueryableFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    public function getFieldNamesToResolve(): array
    {
        return [
            'customPosts',
            'customPostCount',
            'customPostsForAdmin',
            'customPostCountForAdmin',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'customPostsForAdmin',
            'customPostCountForAdmin',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'customPostCount' => SchemaDefinition::TYPE_INT,
            'customPostCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'customPostCount',
            'customPostCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'customPosts',
            'customPostsForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'customPosts' => $this->translationAPI->__('Custom posts', 'pop-posts'),
            'customPostCount' => $this->translationAPI->__('Number of custom posts', 'pop-posts'),
            'customPostsForAdmin' => $this->translationAPI->__('[Unrestricted] Custom posts', 'pop-posts'),
            'customPostCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of custom posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'customPosts' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTLIST
            ],
            'customPostsForAdmin' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTLIST
            ],
            'customPostCount' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_UNIONCUSTOMPOSTCOUNT
            ],
            'customPostCountForAdmin' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINUNIONCUSTOMPOSTCOUNT
            ],
            default => parent::getFieldDataFilteringModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostsForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getCustomPostListDefaultLimit(),
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($objectTypeResolver, $fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostsForAdmin':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getCustomPostListMaxLimit(),
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors[] = $maybeError;
                }
                break;
        }
        return $errors;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        return [];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            $this->getQuery($objectTypeResolver, $resultItem, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostsForAdmin':
                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'customPostCount':
            case 'customPostCountForAdmin':
                return $customPostTypeAPI->getCustomPostCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostsForAdmin':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolverClass(CustomPostUnionTypeResolver::class);
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
