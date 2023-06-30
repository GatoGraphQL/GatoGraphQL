<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\SchemaHooks;

use PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput;
use PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput;
use PoPCMSSchema\Tags\FilterInputs\TagTaxonomyFilterInput;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractAddTagFilterInputObjectTypeHookSet extends AbstractHookSet
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TagSlugsFilterInput $tagSlugsFilterInput = null;
    private ?TagIDsFilterInput $tagIDsFilterInput = null;
    private ?TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver = null;
    private ?TagTaxonomyFilterInput $tagTaxonomyFilterInput = null;

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
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setTagSlugsFilterInput(TagSlugsFilterInput $tagSlugsFilterInput): void
    {
        $this->tagSlugsFilterInput = $tagSlugsFilterInput;
    }
    final protected function getTagSlugsFilterInput(): TagSlugsFilterInput
    {
        if ($this->tagSlugsFilterInput === null) {
            /** @var TagSlugsFilterInput */
            $tagSlugsFilterInput = $this->instanceManager->getInstance(TagSlugsFilterInput::class);
            $this->tagSlugsFilterInput = $tagSlugsFilterInput;
        }
        return $this->tagSlugsFilterInput;
    }
    final public function setTagIDsFilterInput(TagIDsFilterInput $tagIDsFilterInput): void
    {
        $this->tagIDsFilterInput = $tagIDsFilterInput;
    }
    final protected function getTagIDsFilterInput(): TagIDsFilterInput
    {
        if ($this->tagIDsFilterInput === null) {
            /** @var TagIDsFilterInput */
            $tagIDsFilterInput = $this->instanceManager->getInstance(TagIDsFilterInput::class);
            $this->tagIDsFilterInput = $tagIDsFilterInput;
        }
        return $this->tagIDsFilterInput;
    }
    final public function setTagTaxonomyEnumStringScalarTypeResolver(TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver): void
    {
        $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getTagTaxonomyEnumStringScalarTypeResolver(): TagTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->tagTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var TagTaxonomyEnumStringScalarTypeResolver */
            $tagTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(TagTaxonomyEnumStringScalarTypeResolver::class);
            $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->tagTaxonomyEnumStringScalarTypeResolver;
    }
    final public function setTagTaxonomyFilterInput(TagTaxonomyFilterInput $tagTaxonomyFilterInput): void
    {
        $this->tagTaxonomyFilterInput = $tagTaxonomyFilterInput;
    }
    final protected function getTagTaxonomyFilterInput(): TagTaxonomyFilterInput
    {
        if ($this->tagTaxonomyFilterInput === null) {
            /** @var TagTaxonomyFilterInput */
            $tagTaxonomyFilterInput = $this->instanceManager->getInstance(TagTaxonomyFilterInput::class);
            $this->tagTaxonomyFilterInput = $tagTaxonomyFilterInput;
        }
        return $this->tagTaxonomyFilterInput;
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->getInputFieldNameTypeResolvers(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->getInputFieldDescription(...),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            $this->getInputFieldTypeModifiers(...),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            $this->getInputFieldFilterInput(...),
            10,
            3
        );
    }

    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'tagIDs' => $this->getIDScalarTypeResolver(),
                'tagSlugs' => $this->getStringScalarTypeResolver(),
            ],
            $this->addTagTaxonomyFilterInput() ? [
                'tagTaxonomy' => $this->getTagTaxonomyEnumStringScalarTypeResolver(),
            ] : [],
        );
    }

    abstract protected function getInputObjectTypeResolverClass(): string;

    protected function addTagTaxonomyFilterInput(): bool
    {
        return false;
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'tagIDs' => $this->__('Get results from the tags with given IDs', 'tags'),
            'tagSlugs' => $this->__('Get results from the tags with given slug', 'tags'),
            'tagTaxonomy' => $this->__('Get results from the tags with given taxonomy', 'tags'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldTypeModifiers(
        int $inputFieldTypeModifiers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): int {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
            return $inputFieldTypeModifiers;
        }
        return match ($inputFieldName) {
            'tagIDs',
            'tagSlugs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => $inputFieldTypeModifiers,
        };
    }

    public function getInputFieldFilterInput(
        ?FilterInputInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputInterface {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'tagIDs' => $this->getTagIDsFilterInput(),
            'tagSlugs' => $this->getTagSlugsFilterInput(),
            'tagTaxonomy' => $this->getTagTaxonomyFilterInput(),
            default => $inputFieldFilterInput,
        };
    }
}
