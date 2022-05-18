<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ComponentProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Tags\FilterInputProcessors\FilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';
    public final const COMPONENT_FILTERINPUT_TAG_IDS = 'filterinput-tag-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_TAG_SLUGS],
            [self::class, self::COMPONENT_FILTERINPUT_TAG_IDS],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_SLUGS],
            self::COMPONENT_FILTERINPUT_TAG_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_IDS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => 'tagSlugs',
            self::COMPONENT_FILTERINPUT_TAG_IDS => 'tagIDs',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_TAG_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS,
            self::COMPONENT_FILTERINPUT_TAG_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => $this->__('Limit results to elements with the given tags', 'tags'),
            self::COMPONENT_FILTERINPUT_TAG_IDS => $this->__('Limit results to elements with the given ids', 'tags'),
            default => null,
        };
    }
}
