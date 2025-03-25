<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMetaMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

abstract class AbstractSetMetaOnCategoryInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CategoryByOneofInputObjectTypeResolver $categoryByOneofInputObjectTypeResolver = null;
    private ?CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver = null;

    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
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
    final protected function getCategoryByOneofInputObjectTypeResolver(): CategoryByOneofInputObjectTypeResolver
    {
        if ($this->categoryByOneofInputObjectTypeResolver === null) {
            /** @var CategoryByOneofInputObjectTypeResolver */
            $categoryByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryByOneofInputObjectTypeResolver::class);
            $this->categoryByOneofInputObjectTypeResolver = $categoryByOneofInputObjectTypeResolver;
        }
        return $this->categoryByOneofInputObjectTypeResolver;
    }
    final protected function getCategoryTaxonomyEnumStringScalarTypeResolver(): CategoryTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->categoryTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var CategoryTaxonomyEnumStringScalarTypeResolver */
            $categoryTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(CategoryTaxonomyEnumStringScalarTypeResolver::class);
            $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->categoryTaxonomyEnumStringScalarTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set categories on a custom post', 'comment-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addTaxonomyInputField() ? [
                MutationInputProperties::TAXONOMY => $this->getCategoryTaxonomyEnumStringScalarTypeResolver(),
            ] : [],
            $this->addCustomPostInputField() ? [
                MutationInputProperties::CATEGORY_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::CATEGORY_BY => $this->getCategoryByOneofInputObjectTypeResolver(),
                MutationInputProperties::APPEND => $this->getBooleanScalarTypeResolver(),
            ],
        );
    }

    abstract protected function addTaxonomyInputField(): bool;
    abstract protected function addCustomPostInputField(): bool;
    abstract protected function getEntityName(): string;
    abstract protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::TAXONOMY => $this->__('The category taxonomy', 'custompost-tag-mutations'),
            MutationInputProperties::CATEGORY_ID => sprintf(
                $this->__('The ID of the %s', 'custompost-categorymeta-mutations'),
                $this->getEntityName()
            ),
            MutationInputProperties::CATEGORY_BY => sprintf(
                $this->__('The categories to set, of type \'%s\'', 'custompost-categorymeta-mutations'),
                $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName()
            ),
            MutationInputProperties::APPEND => $this->__('Append the categories to the existing ones?', 'custompost-categorymeta-mutations'),
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
            MutationInputProperties::CATEGORY_ID,
            MutationInputProperties::CATEGORY_BY
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
