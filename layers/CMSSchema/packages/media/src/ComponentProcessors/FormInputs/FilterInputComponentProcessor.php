<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ComponentProcessors\FormInputs;

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

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setMimeTypesFilterInput(MimeTypesFilterInput $mimeTypesFilterInput): void
    {
        $this->mimeTypesFilterInput = $mimeTypesFilterInput;
    }
    final protected function getMimeTypesFilterInput(): MimeTypesFilterInput
    {
        return $this->mimeTypesFilterInput ??= $this->instanceManager->getInstance(MimeTypesFilterInput::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_MIME_TYPES],
        );
    }

    public function getFilterInput(array $component): ?FilterInputInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => $this->getMimeTypesFilterInput(),
            default => null,
        };
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => 'mimeTypes',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => $this->getStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_MIME_TYPES => $this->__('Limit results to elements with the given mime types', 'media'),
            default => null,
        };
    }
}
