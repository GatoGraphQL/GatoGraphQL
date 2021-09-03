<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
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

    public function getSchemaFieldType(ObjectTypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'customPosts' => SchemaDefinition::TYPE_ID,
            'customPostCount' => SchemaDefinition::TYPE_INT,
            'customPostsForAdmin' => SchemaDefinition::TYPE_ID,
            'customPostCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'customPostCount',
            'customPostCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'customPosts',
            'customPostsForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'customPosts' => $this->translationAPI->__('Custom posts', 'pop-posts'),
            'customPostCount' => $this->translationAPI->__('Number of custom posts', 'pop-posts'),
            'customPostsForAdmin' => $this->translationAPI->__('[Unrestricted] Custom posts', 'pop-posts'),
            'customPostCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of custom posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?array
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
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(ObjectTypeResolverInterface $typeResolver, string $fieldName): array
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
        return parent::getFieldDataFilteringDefaultValues($typeResolver, $fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $typeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $typeResolver,
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
        ObjectTypeResolverInterface $typeResolver,
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
        ObjectTypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($typeResolver, $fieldName, $fieldArgs),
            $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostsForAdmin':
                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'customPostCount':
            case 'customPostCountForAdmin':
                return $customPostTypeAPI->getCustomPostCount($query);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostsForAdmin':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
