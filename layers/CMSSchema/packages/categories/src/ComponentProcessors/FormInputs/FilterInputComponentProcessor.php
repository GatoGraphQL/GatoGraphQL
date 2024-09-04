<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ComponentProcessors\FormInputs;

use PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput;
use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_CATEGORY_IDS = 'filterinput-category-ids';
    public final const COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY = 'filterinput-generic-category-taxonomy';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CategoryIDsFilterInput $categoryIDsFilterInput = null;
    private ?TaxonomyFilterInput $taxonomyFilterInput = null;
    private ?CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final public function setCategoryIDsFilterInput(CategoryIDsFilterInput $categoryIDsFilterInput): void
    {
        $this->categoryIDsFilterInput = $categoryIDsFilterInput;
    }
    final protected function getCategoryIDsFilterInput(): CategoryIDsFilterInput
    {
        if ($this->categoryIDsFilterInput === null) {
            /** @var CategoryIDsFilterInput */
            $categoryIDsFilterInput = $this->instanceManager->getInstance(CategoryIDsFilterInput::class);
            $this->categoryIDsFilterInput = $categoryIDsFilterInput;
        }
        return $this->categoryIDsFilterInput;
    }
    final public function setTaxonomyFilterInput(TaxonomyFilterInput $taxonomyFilterInput): void
    {
        $this->taxonomyFilterInput = $taxonomyFilterInput;
    }
    final protected function getTaxonomyFilterInput(): TaxonomyFilterInput
    {
        if ($this->taxonomyFilterInput === null) {
            /** @var TaxonomyFilterInput */
            $taxonomyFilterInput = $this->instanceManager->getInstance(TaxonomyFilterInput::class);
            $this->taxonomyFilterInput = $taxonomyFilterInput;
        }
        return $this->taxonomyFilterInput;
    }
    final public function setCategoryTaxonomyEnumStringScalarTypeResolver(CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver): void
    {
        $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getCategoryTaxonomyEnumStringScalarTypeResolver(): CategoryTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->categoryTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var CategoryTaxonomyEnumStringScalarTypeResolver */
            $categoryTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(CategoryTaxonomyEnumStringScalarTypeResolver::class);
            $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->categoryTaxonomyEnumStringScalarTypeResolver;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS,
            self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->getCategoryIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY => $this->getTaxonomyFilterInput(),
            default => null,
        };
    }

    public function getInputClass(Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(Component $component): string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => 'categoryIDs',
            self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY => 'taxonomy',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY => $this->getCategoryTaxonomyEnumStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            // self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY => SchemaTypeModifiers::MANDATORY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->__('Limit results to elements with the given ids', 'categories'),
            self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY => $this->__('Category taxonomy', 'categories'),
            default => null,
        };
    }
}
