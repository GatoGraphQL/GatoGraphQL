<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;

abstract class AbstractCreateOrUpdateCategoryInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements UpdateCategoryInputObjectTypeResolverInterface, CreateCategoryInputObjectTypeResolverInterface
{
    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CustomPostContentAsOneofInputObjectTypeResolver $customPostContentAsOneofInputObjectTypeResolver = null;

    final public function setCustomPostStatusEnumTypeResolver(CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver): void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    final protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        if ($this->customPostStatusEnumTypeResolver === null) {
            /** @var CustomPostStatusEnumTypeResolver */
            $customPostStatusEnumTypeResolver = $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
            $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
        }
        return $this->customPostStatusEnumTypeResolver;
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
    final public function setCustomPostContentAsOneofInputObjectTypeResolver(CustomPostContentAsOneofInputObjectTypeResolver $customPostContentAsOneofInputObjectTypeResolver): void
    {
        $this->customPostContentAsOneofInputObjectTypeResolver = $customPostContentAsOneofInputObjectTypeResolver;
    }
    final protected function getCustomPostContentAsOneofInputObjectTypeResolver(): CustomPostContentAsOneofInputObjectTypeResolver
    {
        if ($this->customPostContentAsOneofInputObjectTypeResolver === null) {
            /** @var CustomPostContentAsOneofInputObjectTypeResolver */
            $customPostContentAsOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostContentAsOneofInputObjectTypeResolver::class);
            $this->customPostContentAsOneofInputObjectTypeResolver = $customPostContentAsOneofInputObjectTypeResolver;
        }
        return $this->customPostContentAsOneofInputObjectTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a category', 'category-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addCustomPostInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::TITLE => $this->getStringScalarTypeResolver(),
                MutationInputProperties::CONTENT_AS => $this->getContentAsOneofInputObjectTypeResolver(),
                MutationInputProperties::EXCERPT => $this->getStringScalarTypeResolver(),
                MutationInputProperties::SLUG => $this->getStringScalarTypeResolver(),
                MutationInputProperties::STATUS => $this->getCustomPostStatusEnumTypeResolver(),
            ]
        );
    }

    protected function getContentAsOneofInputObjectTypeResolver(): AbstractCustomPostContentAsOneofInputObjectTypeResolver
    {
        return $this->getCustomPostContentAsOneofInputObjectTypeResolver();
    }

    abstract protected function addCustomPostInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the category to update', 'category-mutations'),
            MutationInputProperties::TITLE => $this->__('The title of the category', 'category-mutations'),
            MutationInputProperties::CONTENT_AS => $this->__('The content of the category', 'category-mutations'),
            MutationInputProperties::EXCERPT => $this->__('The excerpt of the category', 'category-mutations'),
            MutationInputProperties::SLUG => $this->__('The slug of the category', 'category-mutations'),
            MutationInputProperties::STATUS => $this->__('The status of the category', 'category-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
