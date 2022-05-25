<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Categories\FilterInputProcessors\FilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_CATEGORY_IDS = 'filterinput-category-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?FilterInputProcessor $filterInputProcessor = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setFilterInputProcessor(FilterInputProcessor $filterInputProcessor): void
    {
        $this->filterInputProcessor = $filterInputProcessor;
    }
    final protected function getFilterInputProcessor(): FilterInputProcessor
    {
        return $this->filterInputProcessor ??= $this->instanceManager->getInstance(FilterInputProcessor::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_CATEGORY_IDS],
        );
    }

    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CATEGORY_IDS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => 'categoryIDs',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CATEGORY_IDS => $this->__('Limit results to elements with the given ids', 'categories'),
            default => null,
        };
    }
}
