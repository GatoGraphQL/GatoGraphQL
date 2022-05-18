<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\ComponentProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\GenericCustomPosts\FilterInputProcessors\FilterInputProcessor;
use PoPCMSSchema\GenericCustomPosts\TypeResolvers\EnumType\GenericCustomPostEnumTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES = 'filterinput-genericcustomposttypes';

    private ?GenericCustomPostEnumTypeResolver $genericCustomPostEnumTypeResolver = null;

    final public function setGenericCustomPostEnumTypeResolver(GenericCustomPostEnumTypeResolver $genericCustomPostEnumTypeResolver): void
    {
        $this->genericCustomPostEnumTypeResolver = $genericCustomPostEnumTypeResolver;
    }
    final protected function getGenericCustomPostEnumTypeResolver(): GenericCustomPostEnumTypeResolver
    {
        return $this->genericCustomPostEnumTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostEnumTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }
    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                $names = array(
                    self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => 'customPostTypes',
                );
                return $names[$component[1]];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => $this->getGenericCustomPostEnumTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDefaultValue(array $component): mixed
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => $this->getGenericCustomPostEnumTypeResolver()->getConsolidatedEnumValues(),
            default => null,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => $this->__('Return results from Custom Post Types', 'customposts'),
            default => null,
        };
    }
}
