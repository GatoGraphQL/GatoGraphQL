<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ComponentProcessors\FormInputs;

use PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput;
use PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';
    public final const COMPONENT_FILTERINPUT_TAG_IDS = 'filterinput-tag-ids';
    public final const COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY = 'filterinput-generic-tag-taxonomy';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TagSlugsFilterInput $tagSlugsFilterInput = null;
    private ?TagIDsFilterInput $tagIDsFilterInput = null;
    private ?TaxonomyFilterInput $taxonomyFilterInput = null;
    private ?TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setTagSlugsFilterInput(TagSlugsFilterInput $tagSlugsFilterInput): void
    {
        $this->tagSlugsFilterInput = $tagSlugsFilterInput;
    }
    final protected function getTagSlugsFilterInput(): TagSlugsFilterInput
    {
        /** @var TagSlugsFilterInput */
        return $this->tagSlugsFilterInput ??= $this->instanceManager->getInstance(TagSlugsFilterInput::class);
    }
    final public function setTagIDsFilterInput(TagIDsFilterInput $tagIDsFilterInput): void
    {
        $this->tagIDsFilterInput = $tagIDsFilterInput;
    }
    final protected function getTagIDsFilterInput(): TagIDsFilterInput
    {
        /** @var TagIDsFilterInput */
        return $this->tagIDsFilterInput ??= $this->instanceManager->getInstance(TagIDsFilterInput::class);
    }
    final public function setTaxonomyFilterInput(TaxonomyFilterInput $taxonomyFilterInput): void
    {
        $this->taxonomyFilterInput = $taxonomyFilterInput;
    }
    final protected function getTaxonomyFilterInput(): TaxonomyFilterInput
    {
        /** @var TaxonomyFilterInput */
        return $this->taxonomyFilterInput ??= $this->instanceManager->getInstance(TaxonomyFilterInput::class);
    }
    final public function setTagTaxonomyEnumStringScalarTypeResolver(TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver): void
    {
        $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getTagTaxonomyEnumStringScalarTypeResolver(): TagTaxonomyEnumStringScalarTypeResolver
    {
        /** @var TagTaxonomyEnumStringScalarTypeResolver */
        return $this->tagTaxonomyEnumStringScalarTypeResolver ??= $this->instanceManager->getInstance(TagTaxonomyEnumStringScalarTypeResolver::class);
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_TAG_SLUGS,
            self::COMPONENT_FILTERINPUT_TAG_IDS,
            self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => $this->getTagSlugsFilterInput(),
            self::COMPONENT_FILTERINPUT_TAG_IDS => $this->getTagIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY => $this->getTaxonomyFilterInput(),
            default => null,
        };
    }

    public function getInputClass(Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(Component $component): string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => 'tagSlugs',
            self::COMPONENT_FILTERINPUT_TAG_IDS => 'tagIDs',
            self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY => 'taxonomy',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_TAG_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY => $this->getTagTaxonomyEnumStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS,
            self::COMPONENT_FILTERINPUT_TAG_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY
                => SchemaTypeModifiers::MANDATORY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_TAG_SLUGS => $this->__('Limit results to elements with the given tags', 'tags'),
            self::COMPONENT_FILTERINPUT_TAG_IDS => $this->__('Limit results to elements with the given ids', 'tags'),
            self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY => $this->__('Tag taxonomy', 'tags'),
            default => null,
        };
    }
}
