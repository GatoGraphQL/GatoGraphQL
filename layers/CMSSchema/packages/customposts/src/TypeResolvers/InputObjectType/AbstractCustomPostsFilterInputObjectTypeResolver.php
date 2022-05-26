<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\FilterInputProcessors\CustomPostStatusFilterInputProcessor;
use PoPCMSSchema\CustomPosts\FilterInputProcessors\UnionCustomPostTypesFilterInputProcessor;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SearchFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;

abstract class AbstractCustomPostsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver = null;
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;
    private ?CustomPostEnumTypeResolver $customPostEnumTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CustomPostStatusFilterInputProcessor $customPostStatusFilterInputProcessor = null;
    private ?UnionCustomPostTypesFilterInputProcessor $unionCustomPostTypesFilterInputProcessor = null;
    private ?SearchFilterInputProcessor $seachFilterInputProcessor = null;

    final public function setDateQueryInputObjectTypeResolver(DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver): void
    {
        $this->dateQueryInputObjectTypeResolver = $dateQueryInputObjectTypeResolver;
    }
    final protected function getDateQueryInputObjectTypeResolver(): DateQueryInputObjectTypeResolver
    {
        return $this->dateQueryInputObjectTypeResolver ??= $this->instanceManager->getInstance(DateQueryInputObjectTypeResolver::class);
    }
    final public function setFilterCustomPostStatusEnumTypeResolver(FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver): void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        return $this->filterCustomPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
    }
    final public function setCustomPostEnumTypeResolver(CustomPostEnumTypeResolver $customPostEnumTypeResolver): void
    {
        $this->customPostEnumTypeResolver = $customPostEnumTypeResolver;
    }
    final protected function getCustomPostEnumTypeResolver(): CustomPostEnumTypeResolver
    {
        return $this->customPostEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostEnumTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setCustomPostStatusFilterInputProcessor(CustomPostStatusFilterInputProcessor $customPostStatusFilterInputProcessor): void
    {
        $this->customPostStatusFilterInputProcessor = $customPostStatusFilterInputProcessor;
    }
    final protected function getCustomPostStatusFilterInputProcessor(): CustomPostStatusFilterInputProcessor
    {
        return $this->customPostStatusFilterInputProcessor ??= $this->instanceManager->getInstance(CustomPostStatusFilterInputProcessor::class);
    }
    final public function setUnionCustomPostTypesFilterInputProcessor(UnionCustomPostTypesFilterInputProcessor $unionCustomPostTypesFilterInputProcessor): void
    {
        $this->unionCustomPostTypesFilterInputProcessor = $unionCustomPostTypesFilterInputProcessor;
    }
    final protected function getUnionCustomPostTypesFilterInputProcessor(): UnionCustomPostTypesFilterInputProcessor
    {
        return $this->unionCustomPostTypesFilterInputProcessor ??= $this->instanceManager->getInstance(UnionCustomPostTypesFilterInputProcessor::class);
    }
    final public function setSearchFilterInputProcessor(SearchFilterInputProcessor $seachFilterInputProcessor): void
    {
        $this->seachFilterInputProcessor = $seachFilterInputProcessor;
    }
    final protected function getSearchFilterInputProcessor(): SearchFilterInputProcessor
    {
        return $this->seachFilterInputProcessor ??= $this->instanceManager->getInstance(SearchFilterInputProcessor::class);
    }

    public function getAdminInputFieldNames(): array
    {
        $adminInputFieldNames = parent::getAdminInputFieldNames();
        if ($this->treatCustomPostStatusAsAdminData()) {
            $adminInputFieldNames[] = 'status';
        }
        return $adminInputFieldNames;
    }

    protected function treatCustomPostStatusAsAdminData(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->treatCustomPostStatusAsAdminData();
    }

    protected function addCustomPostInputFields(): bool
    {
        return false;
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'status' => $this->getFilterCustomPostStatusEnumTypeResolver(),
                'search' => $this->getStringScalarTypeResolver(),
                'dateQuery' => $this->getDateQueryInputObjectTypeResolver(),
            ],
            $this->addCustomPostInputFields() ? [
                'customPostTypes' => $this->getCustomPostEnumTypeResolver(),
            ] : []
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'status' => $this->__('Custom post status', 'customposts'),
            'search' => $this->__('Search for custom posts containing the given string', 'customposts'),
            'dateQuery' => $this->__('Filter custom posts based on date', 'customposts'),
            'customPostTypes' => $this->__('Filter custom posts of given types', 'customposts'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'status' => [
                CustomPostStatus::PUBLISH,
            ],
            'customPostTypes' => $this->getCustomPostEnumTypeResolver()->getConsolidatedEnumValues(),
            default => parent::getInputFieldDefaultValue($inputFieldName)
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'status',
            'customPostTypes'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'status' => $this->getCustomPostStatusFilterInputProcessor(),
            'search' => $this->getSearchFilterInputProcessor(),
            'customPostTypes' => $this->getUnionCustomPostTypesFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
