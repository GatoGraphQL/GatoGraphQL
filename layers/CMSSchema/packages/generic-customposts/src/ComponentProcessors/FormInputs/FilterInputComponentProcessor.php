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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_GENERICCUSTOMPOSTTYPES],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($componentVariation);
    }
    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES:
                $names = array(
                    self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => 'customPostTypes',
                );
                return $names[$componentVariation[1]];
        }

        return parent::getName($componentVariation);
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => $this->getGenericCustomPostEnumTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDefaultValue(array $componentVariation): mixed
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => $this->getGenericCustomPostEnumTypeResolver()->getConsolidatedEnumValues(),
            default => null,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_GENERICCUSTOMPOSTTYPES => $this->__('Return results from Custom Post Types', 'customposts'),
            default => null,
        };
    }
}
