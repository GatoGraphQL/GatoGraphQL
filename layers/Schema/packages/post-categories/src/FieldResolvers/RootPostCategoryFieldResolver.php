<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers;

use PoP\ComponentModel\Facades\FilterInputProcessors\FilterInputProcessorManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\PostCategories\ModuleProcessors\PostCategoryFilterInputContainerModuleProcessor;
use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class RootPostCategoryFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'postCategory',
            'postCategoryBySlug',
            'postCategories',
            'postCategoryCount',
            'postCategoryNames',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'postCategory' => SchemaDefinition::TYPE_ID,
            'postCategoryBySlug' => SchemaDefinition::TYPE_ID,
            'postCategories' => SchemaDefinition::TYPE_ID,
            'postCategoryCount' => SchemaDefinition::TYPE_INT,
            'postCategoryNames' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'postCategoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'postCategories',
            'postCategoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'postCategory' => $this->translationAPI->__('Post category with a specific ID', 'post-categories'),
            'postCategoryBySlug' => $this->translationAPI->__('Post category with a specific slug', 'post-categories'),
            'postCategories' => $this->translationAPI->__('Post categories', 'post-categories'),
            'postCategoryCount' => $this->translationAPI->__('Number of post categories', 'post-categories'),
            'postCategoryNames' => $this->translationAPI->__('Names of the post categories', 'post-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'postCategory':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'id',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The category ID', 'post-categories'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'postCategoryBySlug':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'slug',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The category slug', 'post-categories'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'postCategories':
            case 'postCategoryCount':
            case 'postCategoryNames':
                $schemaFieldArgs = array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
                // By default fetch top-level categories: "parent-id" => 0
                $filterInputName = $this->getParentIDFieldArgName();
                foreach ($schemaFieldArgs as &$schemaFieldArg) {
                    if ($schemaFieldArg['name'] !== $filterInputName) {
                        continue;
                    }
                    $schemaFieldArg[SchemaDefinition::ARGNAME_DEFAULT_VALUE] = 0;
                    break;
                }
                return $schemaFieldArgs;
        }
        return $schemaFieldArgs;
    }

    protected function getParentIDFieldArgName(): string
    {
        $filterInputProcessorManager = FilterInputProcessorManagerFacade::getInstance();
        $filterInput = [
            CommonFilterInputModuleProcessor::class,
            CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID
        ];
        /** @var FilterInputModuleProcessor */
        $filterInputProcessor = $filterInputProcessorManager->getProcessor($filterInput);
        return $filterInputProcessor->getName($filterInput);
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'postCategories':
            case 'postCategoryCount':
            case 'postCategoryNames':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'postCategories' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORIES],
            'postCategoryCount' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT],
            'postCategoryNames' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORIES],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        };
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
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'postCategory':
            case 'postCategoryBySlug':
                $query = [];
                if ($fieldName == 'postCategory') {
                    $query['include'] = [$fieldArgs['id']];
                } elseif ($fieldName == 'postCategoryBySlug') {
                    $query['slugs'] = [$fieldArgs['slug']];
                }
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                if ($categories = $postCategoryTypeAPI->getCategories($query, $options)) {
                    return $categories[0];
                }
                return null;
            case 'postCategories':
            case 'postCategoryNames':
                $query = [
                    'limit' => ComponentConfiguration::getCategoryListDefaultLimit(),
                ];
                $options = [
                    'return-type' => $fieldName === 'postCategories' ? ReturnTypes::IDS : ReturnTypes::NAMES,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postCategoryTypeAPI->getCategories($query, $options);
            case 'postCategoryCount':
                $query = [];
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postCategoryTypeAPI->getCategoryCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'postCategory':
            case 'postCategoryBySlug':
            case 'postCategories':
                return PostCategoryTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
