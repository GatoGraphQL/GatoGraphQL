<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractCreateOrUpdateTaxonomyTermInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements UpdateTaxonomyTermInputObjectTypeResolverInterface, CreateTaxonomyTermInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

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

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to create or update a taxonomy term', 'taxonomy-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addIDInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            $this->addParentIDInputField() ? [
                MutationInputProperties::PARENT_BY => $this->getTaxonomyTermParentInputObjectTypeResolver(),
            ] : [],
            [
                MutationInputProperties::NAME => $this->getStringScalarTypeResolver(),
                MutationInputProperties::DESCRIPTION => $this->getStringScalarTypeResolver(),
                MutationInputProperties::SLUG => $this->getStringScalarTypeResolver(),
                MutationInputProperties::TAXONOMY => $this->getTaxonomyInputObjectTypeResolver(),
            ]
        );
    }

    abstract protected function getTaxonomyTermParentInputObjectTypeResolver(): InputTypeResolverInterface;
    abstract protected function getTaxonomyInputObjectTypeResolver(): InputTypeResolverInterface;

    abstract protected function addIDInputField(): bool;
    abstract protected function addParentIDInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the taxonomy to update', 'taxonomy-mutations'),
            MutationInputProperties::NAME => $this->__('The name of the taxonomy', 'taxonomy-mutations'),
            MutationInputProperties::DESCRIPTION => $this->__('The description of the taxonomy', 'taxonomy-mutations'),
            MutationInputProperties::SLUG => $this->__('The slug of the taxonomy', 'taxonomy-mutations'),
            MutationInputProperties::TAXONOMY => $this->__('The taxonomy', 'taxonomy-mutations'),
            MutationInputProperties::PARENT_BY => $this->__('The taxonomy\'s parent, or `null` to remove it', 'taxonomy-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID,
                => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::NAME
                => $this->isNameInputFieldMandatory() ? SchemaTypeModifiers::MANDATORY : SchemaTypeModifiers::NONE,
            MutationInputProperties::TAXONOMY => $this->isTaxonomyInputFieldMandatory()
                ? SchemaTypeModifiers::MANDATORY
                : SchemaTypeModifiers::NONE,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    abstract protected function isNameInputFieldMandatory(): bool;
    abstract protected function isTaxonomyInputFieldMandatory(): bool;

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            MutationInputProperties::TAXONOMY => $this->getTaxonomyInputFieldDefaultValue(),
            default
                => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    abstract protected function getTaxonomyInputFieldDefaultValue(): mixed;
}
