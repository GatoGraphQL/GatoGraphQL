<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

abstract class AbstractSetCategoriesOnCustomPostInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CategoriesByOneofInputObjectTypeResolver $categoriesByOneofInputObjectTypeResolver = null;
    private ?CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver = null;

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
    final public function setCategoriesByOneofInputObjectTypeResolver(CategoriesByOneofInputObjectTypeResolver $categoriesByOneofInputObjectTypeResolver): void
    {
        $this->categoriesByOneofInputObjectTypeResolver = $categoriesByOneofInputObjectTypeResolver;
    }
    final protected function getCategoriesByOneofInputObjectTypeResolver(): CategoriesByOneofInputObjectTypeResolver
    {
        if ($this->categoriesByOneofInputObjectTypeResolver === null) {
            /** @var CategoriesByOneofInputObjectTypeResolver */
            $categoriesByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CategoriesByOneofInputObjectTypeResolver::class);
            $this->categoriesByOneofInputObjectTypeResolver = $categoriesByOneofInputObjectTypeResolver;
        }
        return $this->categoriesByOneofInputObjectTypeResolver;
    }
    final public function setCategoryTaxonomyEnumStringScalarTypeResolver(CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver): void
    {
        $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
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
                MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::CATEGORIES_BY => $this->getCategoriesByOneofInputObjectTypeResolver(),
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
            MutationInputProperties::CUSTOMPOST_ID => sprintf(
                $this->__('The ID of the %s', 'custompost-category-mutations'),
                $this->getEntityName()
            ),
            MutationInputProperties::CATEGORIES_BY => sprintf(
                $this->__('The categories to set, of type \'%s\'', 'custompost-category-mutations'),
                $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName()
            ),
            MutationInputProperties::APPEND => $this->__('Append the categories to the existing ones?', 'custompost-category-mutations'),
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
            MutationInputProperties::CATEGORIES_BY
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
