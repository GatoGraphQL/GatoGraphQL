<?php

declare(strict_types=1);

namespace PoPSchema\Pages\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

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

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'childPages':
            case 'childPageCount':
            case 'unrestrictedChildPages':
            case 'unrestrictedChildPageCount':
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
            case 'childPages':
            case 'childPageCount':
            case 'unrestrictedChildPages':
            case 'unrestrictedChildPageCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'childPages' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST
            ],
            'childPageCount' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT
            ],
            'unrestrictedChildPages' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTLIST
            ],
            'unrestrictedChildPageCount' => [
                CustomPostFilterInputContainerModuleProcessor::class,
                CustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTCOUNT
            ],
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
        $page = $resultItem;
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        $query = [
            'status' => [
                Status::PUBLISHED,
            ],
            'parent-page-id' => $typeResolver->getID($page),
        ];
        switch ($fieldName) {
            case 'parentPage':
                return $pageTypeAPI->getParentPageID($page);
            case 'childPages':
            case 'unrestrictedChildPages':
                $query['limit'] = ComponentConfiguration::getPageListDefaultLimit();
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $pageTypeAPI->getPages($query, $options);
            case 'childPageCount':
            case 'unrestrictedChildPageCount':
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
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
