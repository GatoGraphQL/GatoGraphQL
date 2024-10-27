<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Media\FilterInputs\MimeTypesFilterInput;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_MIME_TYPES = 'filterinput-mime-types';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?MimeTypesFilterInput $mimeTypesFilterInput = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getMimeTypesFilterInput(): MimeTypesFilterInput
    {
        if ($this->mimeTypesFilterInput === null) {
            /** @var MimeTypesFilterInput */
            $mimeTypesFilterInput = $this->instanceManager->getInstance(MimeTypesFilterInput::class);
            $this->mimeTypesFilterInput = $mimeTypesFilterInput;
        }
        return $this->mimeTypesFilterInput;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_MIME_TYPES,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => $this->getMimeTypesFilterInput(),
            default => null,
        };
    }

    public function getInputClass(Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(Component $component): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => 'mimeTypes',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => $this->getStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => $this->__('Limit results to elements with the given mime types', 'media'),
            default => null,
        };
    }
}
