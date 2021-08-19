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
            'unrestrictedPage',
            'unrestrictedPageBySlug',
            'unrestrictedPages',
            'unrestrictedPageCount',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'unrestrictedPage',
            'unrestrictedPageBySlug',
            'unrestrictedPages',
            'unrestrictedPageCount',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'page' => $this->translationAPI->__('Page with a specific ID', 'pages'),
            'pageBySlug' => $this->translationAPI->__('Page with a specific slug', 'pages'),
            'pages' => $this->translationAPI->__('Pages', 'pages'),
            'pageCount' => $this->translationAPI->__('Number of pages', 'pages'),
            'unrestrictedPage' => $this->translationAPI->__('[Unrestricted] Page with a specific ID', 'pages'),
            'unrestrictedPageBySlug' => $this->translationAPI->__('[Unrestricted] Page with a specific slug', 'pages'),
            'unrestrictedPages' => $this->translationAPI->__('[Unrestricted] Pages', 'pages'),
            'unrestrictedPageCount' => $this->translationAPI->__('[Unrestricted] Number of pages', 'pages'),
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
            'unrestrictedPage' => SchemaDefinition::TYPE_ID,
            'unrestrictedPageBySlug' => SchemaDefinition::TYPE_ID,
            'unrestrictedPages' => SchemaDefinition::TYPE_ID,
            'unrestrictedPageCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'pageCount',
            'unrestrictedPageCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'pages',
            'unrestrictedPages'
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
            'unrestrictedPages' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTLIST
            ],
            'unrestrictedPageCount' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT
            ],
            'page' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID
            ],
            'unrestrictedPage' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS
            ],
            'pageBySlug' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG
            ],
            'unrestrictedPageBySlug' => [
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
            case 'unrestrictedPages':
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
            case 'unrestrictedPage':
            case 'unrestrictedPageBySlug':
                if ($pages = $pageTypeAPI->getPages($query, ['return-type' => ReturnTypes::IDS])) {
                    return $pages[0];
                }
                return null;
            case 'pages':
            case 'unrestrictedPages':
                return $pageTypeAPI->getPages($query, ['return-type' => ReturnTypes::IDS]);
            case 'pageCount':
            case 'unrestrictedPageCount':
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
            case 'unrestrictedPage':
            case 'unrestrictedPageBySlug':
            case 'unrestrictedPages':
                return PageTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
