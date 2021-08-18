<?php

declare(strict_types=1);

namespace PoPSchema\Pages\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

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
            'unrestrictedChildPages',
            'unrestrictedChildPageCount',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'unrestrictedChildPages',
            'unrestrictedChildPageCount',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'parentPage' => $this->translationAPI->__('Parent page', 'pages'),
            'childPages' => $this->translationAPI->__('Child pages', 'pages'),
            'childPageCount' => $this->translationAPI->__('Number of child pages', 'pages'),
            'unrestrictedChildPages' => $this->translationAPI->__('[Unrestricted] Child pages', 'pages'),
            'unrestrictedChildPageCount' => $this->translationAPI->__('[Unrestricted] Number of child pages', 'pages'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'parentPage' => SchemaDefinition::TYPE_ID,
            'childPages' => SchemaDefinition::TYPE_ID,
            'childPageCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedChildPages' => SchemaDefinition::TYPE_ID,
            'unrestrictedChildPageCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'childPageCount',
            'unrestrictedChildPageCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'childPages',
            'unrestrictedChildPages'
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
            'unrestrictedChildPages' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST
            ],
            'unrestrictedChildPageCount' => [
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
            case 'unrestrictedChildPages':
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

        $options = $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs);
        $query = [
            'parent-id' => $typeResolver->getID($page),
        ];
        switch ($fieldName) {
            case 'childPages':
            case 'unrestrictedChildPages':
                $options['return-type'] = ReturnTypes::IDS;
                return $pageTypeAPI->getPages($query, $options);
            case 'childPageCount':
            case 'unrestrictedChildPageCount':
                return $pageTypeAPI->getPageCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'parentPage':
            case 'childPages':
            case 'unrestrictedChildPages':
                return PageTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
