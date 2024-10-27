<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;

abstract class AbstractCreateOrUpdateCustomPostInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements UpdateCustomPostInputObjectTypeResolverInterface, CreateCustomPostInputObjectTypeResolverInterface
{
    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?CustomPostContentAsOneofInputObjectTypeResolver $customPostContentAsOneofInputObjectTypeResolver = null;

    final protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        if ($this->customPostStatusEnumTypeResolver === null) {
            /** @var CustomPostStatusEnumTypeResolver */
            $customPostStatusEnumTypeResolver = $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
            $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
        }
        return $this->customPostStatusEnumTypeResolver;
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
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
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
        return $this->__('Input to update a custom post', 'custompost-mutations');
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
            MutationInputProperties::ID => $this->__('The ID of the custom post to update', 'custompost-mutations'),
            MutationInputProperties::TITLE => $this->__('The title of the custom post', 'custompost-mutations'),
            MutationInputProperties::CONTENT_AS => $this->__('The content of the custom post', 'custompost-mutations'),
            MutationInputProperties::EXCERPT => $this->__('The excerpt of the custom post', 'custompost-mutations'),
            MutationInputProperties::SLUG => $this->__('The slug of the custom post', 'custompost-mutations'),
            MutationInputProperties::STATUS => $this->__('The status of the custom post', 'custompost-mutations'),
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
