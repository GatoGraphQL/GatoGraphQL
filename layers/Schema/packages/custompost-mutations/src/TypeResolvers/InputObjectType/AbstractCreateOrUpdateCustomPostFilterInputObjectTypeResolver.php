<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;

abstract class AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setCustomPostStatusEnumTypeResolver(CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver): void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    final protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        return $this->customPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
    }
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

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to update a custom post', 'custompost-mutations');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addCustomPostInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::TITLE => $this->getStringScalarTypeResolver(),
                MutationInputProperties::CONTENT => $this->getStringScalarTypeResolver(),
                MutationInputProperties::STATUS => $this->getCustomPostStatusEnumTypeResolver(),
            ]
        );
    }

    abstract protected function addCustomPostInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the custom post to update', 'custompost-mutations'),
            MutationInputProperties::TITLE => $this->__('The title of the custom post', 'custompost-mutations'),
            MutationInputProperties::CONTENT => $this->__('The content of the custom post', 'custompost-mutations'),
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
