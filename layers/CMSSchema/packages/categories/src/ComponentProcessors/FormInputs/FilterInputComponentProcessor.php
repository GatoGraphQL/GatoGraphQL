<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_CATEGORY_IDS = 'filterinput-category-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CategoryIDsFilterInput $categoryIDsFilterInput = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setCategoryIDsFilterInput(CategoryIDsFilterInput $categoryIDsFilterInput): void
    {
        $this->categoryIDsFilterInput = $categoryIDsFilterInput;
    }
    final protected function getCategoryIDsFilterInput(): CategoryIDsFilterInput
    {
        return $this->categoryIDsFilterInput ??= $this->instanceManager->getInstance(CategoryIDsFilterInput::class);
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->getCategoryIDsFilterInput(),
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
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->__('Limit results to elements with the given ids', 'categories'),
            default => null,
        };
    }
}
