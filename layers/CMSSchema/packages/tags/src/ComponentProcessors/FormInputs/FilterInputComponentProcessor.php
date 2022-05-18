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
    public final const MODULE_FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';
    public final const MODULE_FILTERINPUT_TAG_IDS = 'filterinput-tag-ids';

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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_TAG_SLUGS],
            [self::class, self::MODULE_FILTERINPUT_TAG_IDS],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_TAG_SLUGS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_SLUGS],
            self::MODULE_FILTERINPUT_TAG_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_IDS],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_TAG_SLUGS:
            case self::MODULE_FILTERINPUT_TAG_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => 'tagSlugs',
            self::MODULE_FILTERINPUT_TAG_IDS => 'tagIDs',
            default => parent::getName($componentVariation),
        };
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_TAG_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS,
            self::MODULE_FILTERINPUT_TAG_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_TAG_SLUGS => $this->__('Limit results to elements with the given tags', 'tags'),
            self::MODULE_FILTERINPUT_TAG_IDS => $this->__('Limit results to elements with the given ids', 'tags'),
            default => null,
        };
    }
}
