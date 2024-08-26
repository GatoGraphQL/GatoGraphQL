<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\TagsByOneofInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

abstract class AbstractSetTagsOnCustomPostInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?TagsByOneofInputObjectTypeResolver $tagsByOneofInputObjectTypeResolver = null;
    private ?TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
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
    final public function setTagsByOneofInputObjectTypeResolver(TagsByOneofInputObjectTypeResolver $tagsByOneofInputObjectTypeResolver): void
    {
        $this->tagsByOneofInputObjectTypeResolver = $tagsByOneofInputObjectTypeResolver;
    }
    final protected function getTagsByOneofInputObjectTypeResolver(): TagsByOneofInputObjectTypeResolver
    {
        if ($this->tagsByOneofInputObjectTypeResolver === null) {
            /** @var TagsByOneofInputObjectTypeResolver */
            $tagsByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(TagsByOneofInputObjectTypeResolver::class);
            $this->tagsByOneofInputObjectTypeResolver = $tagsByOneofInputObjectTypeResolver;
        }
        return $this->tagsByOneofInputObjectTypeResolver;
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

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set tags on a custom post', 'comment-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addTaxonomyInputField() ? [
                MutationInputProperties::TAXONOMY => $this->getTagTaxonomyEnumStringScalarTypeResolver(),
            ] : [],
            $this->addCustomPostInputField() ? [
                MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::TAGS_BY => $this->getTagsByOneofInputObjectTypeResolver(),
                MutationInputProperties::APPEND => $this->getBooleanScalarTypeResolver(),
            ],
        );
    }

    abstract protected function addTaxonomyInputField(): bool;
    abstract protected function addCustomPostInputField(): bool;
    abstract protected function getEntityName(): string;
    abstract protected function getTagTypeResolver(): TagObjectTypeResolverInterface;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::TAXONOMY => $this->__('The tag taxonomy', 'custompost-tag-mutations'),
            MutationInputProperties::CUSTOMPOST_ID => sprintf(
                $this->__('The ID of the %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            ),
            MutationInputProperties::TAGS_BY => sprintf(
                $this->__('The tags to set, of type \'%s\'', 'custompost-tag-mutations'),
                $this->getTagTypeResolver()->getMaybeNamespacedTypeName()
            ),
            MutationInputProperties::APPEND => $this->__('Append the tags to the existing ones?', 'custompost-tag-mutations'),
            default => null,
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            MutationInputProperties::APPEND => false,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::APPEND
                => SchemaTypeModifiers::NON_NULLABLE,
            MutationInputProperties::TAXONOMY,
            MutationInputProperties::CUSTOMPOST_ID,
            MutationInputProperties::TAGS_BY
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
