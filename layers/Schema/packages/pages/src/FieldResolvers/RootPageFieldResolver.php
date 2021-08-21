<?php

declare(strict_types=1);

namespace PoPSchema\Pages\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\ModuleProcessors\PageFilterInputContainerModuleProcessor;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class RootPageFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'page',
            'pageBySlug',
            'pages',
            'pageCount',
            'pageForAdmin',
            'pageBySlugForAdmin',
            'pagesForAdmin',
            'pageCountForAdmin',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'pageForAdmin',
            'pageBySlugForAdmin',
            'pagesForAdmin',
            'pageCountForAdmin',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'page' => $this->translationAPI->__('Page with a specific ID', 'pages'),
            'pageBySlug' => $this->translationAPI->__('Page with a specific slug', 'pages'),
            'pages' => $this->translationAPI->__('Pages', 'pages'),
            'pageCount' => $this->translationAPI->__('Number of pages', 'pages'),
            'pageForAdmin' => $this->translationAPI->__('[Unrestricted] Page with a specific ID', 'pages'),
            'pageBySlugForAdmin' => $this->translationAPI->__('[Unrestricted] Page with a specific slug', 'pages'),
            'pagesForAdmin' => $this->translationAPI->__('[Unrestricted] Pages', 'pages'),
            'pageCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of pages', 'pages'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'page' => SchemaDefinition::TYPE_ID,
            'pageBySlug' => SchemaDefinition::TYPE_ID,
            'pages' => SchemaDefinition::TYPE_ID,
            'pageCount' => SchemaDefinition::TYPE_INT,
            'pageForAdmin' => SchemaDefinition::TYPE_ID,
            'pageBySlugForAdmin' => SchemaDefinition::TYPE_ID,
            'pagesForAdmin' => SchemaDefinition::TYPE_ID,
            'pageCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'pageCount',
            'pageCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'pages',
            'pagesForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'pages' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_PAGELISTLIST
            ],
            'pageCount' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_PAGELISTCOUNT
            ],
            'pagesForAdmin' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTLIST
            ],
            'pageCountForAdmin' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT
            ],
            'page' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID
            ],
            'pageForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS
            ],
            'pageBySlug' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG
            ],
            'pageBySlugForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS
            ],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'pages':
            case 'pagesForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getPageListDefaultLimit(),
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($typeResolver, $fieldName);
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
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        $query = $this->convertFieldArgsToFilteringQueryArgs($typeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'page':
            case 'pageBySlug':
            case 'pageForAdmin':
            case 'pageBySlugForAdmin':
                if ($pages = $pageTypeAPI->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $pages[0];
                }
                return null;
            case 'pages':
            case 'pagesForAdmin':
                return $pageTypeAPI->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'pageCount':
            case 'pageCountForAdmin':
                return $pageTypeAPI->getPageCount($query);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'page':
            case 'pageBySlug':
            case 'pages':
            case 'pageForAdmin':
            case 'pageBySlugForAdmin':
            case 'pagesForAdmin':
                return PageTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
