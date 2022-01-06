<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;

abstract class AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set categories on a custom post', 'comment-mutations');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addCustomPostInputField() ? [
                MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::CATEGORY_IDS => $this->getIDScalarTypeResolver(),
                MutationInputProperties::APPEND => $this->getBooleanScalarTypeResolver(),
            ],
        );
    }

    abstract protected function addCustomPostInputField(): bool;
    abstract protected function getEntityName(): string;
    abstract protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::CUSTOMPOST_ID => sprintf(
                $this->__('The ID of the %s', 'custompost-category-mutations'),
                $this->getEntityName()
            ),
            MutationInputProperties::CATEGORY_IDS => sprintf(
                $this->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
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
            MutationInputProperties::CUSTOMPOST_ID => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::CATEGORY_IDS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
