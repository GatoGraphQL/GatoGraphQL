<?php

declare(strict_types=1);

namespace PoPSchema\Pages\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class PageFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(PageTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'parentPage',
            'childPages',
            'childPageCount',
            'childPagesForAdmin',
            'childPageCountForAdmin',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'childPagesForAdmin',
            'childPageCountForAdmin',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'parentPage' => $this->translationAPI->__('Parent page', 'pages'),
            'childPages' => $this->translationAPI->__('Child pages', 'pages'),
            'childPageCount' => $this->translationAPI->__('Number of child pages', 'pages'),
            'childPagesForAdmin' => $this->translationAPI->__('[Unrestricted] Child pages', 'pages'),
            'childPageCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of child pages', 'pages'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'parentPage' => SchemaDefinition::TYPE_ID,
            'childPages' => SchemaDefinition::TYPE_ID,
            'childPageCount' => SchemaDefinition::TYPE_INT,
            'childPagesForAdmin' => SchemaDefinition::TYPE_ID,
            'childPageCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'childPageCount',
            'childPageCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'childPages',
            'childPagesForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'childPages' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST
            ],
            'childPageCount' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT
            ],
            'childPagesForAdmin' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST
            ],
            'childPageCountForAdmin' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT
            ],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'childPages':
            case 'childPagesForAdmin':
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
        $page = $resultItem;
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'parentPage':
                return $pageTypeAPI->getParentPageID($page);
        }

        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($typeResolver, $fieldName, $fieldArgs),
            [
                'parent-id' => $typeResolver->getID($page),
            ]
        );
        switch ($fieldName) {
            case 'childPages':
            case 'childPagesForAdmin':
                return $pageTypeAPI->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'childPageCount':
            case 'childPageCountForAdmin':
                return $pageTypeAPI->getPageCount($query);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'parentPage':
            case 'childPages':
            case 'childPagesForAdmin':
                return PageTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
