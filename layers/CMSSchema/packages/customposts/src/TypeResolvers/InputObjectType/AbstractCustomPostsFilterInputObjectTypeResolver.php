<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\FilterInputs\CustomPostStatusFilterInput;
use PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugsFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;

abstract class AbstractCustomPostsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver = null;
    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;
    private ?CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CustomPostStatusFilterInput $customPostStatusFilterInput = null;
    private ?UnionCustomPostTypesFilterInput $unionCustomPostTypesFilterInput = null;
    private ?SearchFilterInput $searchFilterInput = null;
    private ?SlugsFilterInput $slugsFilterInput = null;

    final protected function getDateQueryInputObjectTypeResolver(): DateQueryInputObjectTypeResolver
    {
        if ($this->dateQueryInputObjectTypeResolver === null) {
            /** @var DateQueryInputObjectTypeResolver */
            $dateQueryInputObjectTypeResolver = $this->instanceManager->getInstance(DateQueryInputObjectTypeResolver::class);
            $this->dateQueryInputObjectTypeResolver = $dateQueryInputObjectTypeResolver;
        }
        return $this->dateQueryInputObjectTypeResolver;
    }
    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        if ($this->filterCustomPostStatusEnumTypeResolver === null) {
            /** @var FilterCustomPostStatusEnumTypeResolver */
            $filterCustomPostStatusEnumTypeResolver = $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
            $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
        }
        return $this->filterCustomPostStatusEnumTypeResolver;
    }
    final protected function getCustomPostEnumStringScalarTypeResolver(): CustomPostEnumStringScalarTypeResolver
    {
        if ($this->customPostEnumStringScalarTypeResolver === null) {
            /** @var CustomPostEnumStringScalarTypeResolver */
            $customPostEnumStringScalarTypeResolver = $this->instanceManager->getInstance(CustomPostEnumStringScalarTypeResolver::class);
            $this->customPostEnumStringScalarTypeResolver = $customPostEnumStringScalarTypeResolver;
        }
        return $this->customPostEnumStringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getCustomPostStatusFilterInput(): CustomPostStatusFilterInput
    {
        if ($this->customPostStatusFilterInput === null) {
            /** @var CustomPostStatusFilterInput */
            $customPostStatusFilterInput = $this->instanceManager->getInstance(CustomPostStatusFilterInput::class);
            $this->customPostStatusFilterInput = $customPostStatusFilterInput;
        }
        return $this->customPostStatusFilterInput;
    }
    final protected function getUnionCustomPostTypesFilterInput(): UnionCustomPostTypesFilterInput
    {
        if ($this->unionCustomPostTypesFilterInput === null) {
            /** @var UnionCustomPostTypesFilterInput */
            $unionCustomPostTypesFilterInput = $this->instanceManager->getInstance(UnionCustomPostTypesFilterInput::class);
            $this->unionCustomPostTypesFilterInput = $unionCustomPostTypesFilterInput;
        }
        return $this->unionCustomPostTypesFilterInput;
    }
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        if ($this->searchFilterInput === null) {
            /** @var SearchFilterInput */
            $searchFilterInput = $this->instanceManager->getInstance(SearchFilterInput::class);
            $this->searchFilterInput = $searchFilterInput;
        }
        return $this->searchFilterInput;
    }
    final protected function getSlugsFilterInput(): SlugsFilterInput
    {
        if ($this->slugsFilterInput === null) {
            /** @var SlugsFilterInput */
            $slugsFilterInput = $this->instanceManager->getInstance(SlugsFilterInput::class);
            $this->slugsFilterInput = $slugsFilterInput;
        }
        return $this->slugsFilterInput;
    }

    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames(): array
    {
        $sensitiveInputFieldNames = parent::getSensitiveInputFieldNames();
        if ($this->treatCustomPostStatusAsSensitiveData()) {
            $sensitiveInputFieldNames[] = 'status';
        }
        return $sensitiveInputFieldNames;
    }

    protected function treatCustomPostStatusAsSensitiveData(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->treatCustomPostStatusAsSensitiveData();
    }

    protected function addCustomPostInputFields(): bool
    {
        return false;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'status' => $this->getFilterCustomPostStatusEnumTypeResolver(),
                'search' => $this->getStringScalarTypeResolver(),
                'slugs' => $this->getStringScalarTypeResolver(),
                'dateQuery' => $this->getDateQueryInputObjectTypeResolver(),
            ],
            $this->addCustomPostInputFields() ? [
                'customPostTypes' => $this->getCustomPostEnumStringScalarTypeResolver(),
            ] : []
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'status' => $this->__('Custom post status', 'customposts'),
            'search' => $this->__('Search for custom posts containing the given string', 'customposts'),
            'slugs' => $this->__('Search for custom posts with the given slugs', 'customposts'),
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
            'customPostTypes' => $this->getCustomPostEnumStringScalarTypeResolver()->getConsolidatedPossibleValues(),
            default => parent::getInputFieldDefaultValue($inputFieldName)
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'slugs',
            'status',
            'customPostTypes'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'status' => $this->getCustomPostStatusFilterInput(),
            'search' => $this->getSearchFilterInput(),
            'slugs' => $this->getSlugsFilterInput(),
            'customPostTypes' => $this->getUnionCustomPostTypesFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
