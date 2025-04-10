<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\NullableListValueJSONObjectScalarTypeResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

abstract class AbstractSetEntityMetaInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements SetEntityMetaInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?NullableListValueJSONObjectScalarTypeResolver $nullableListValueJSONObjectScalarTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getNullableListValueJSONObjectScalarTypeResolver(): NullableListValueJSONObjectScalarTypeResolver
    {
        if ($this->nullableListValueJSONObjectScalarTypeResolver === null) {
            /** @var NullableListValueJSONObjectScalarTypeResolver */
            $nullableListValueJSONObjectScalarTypeResolver = $this->instanceManager->getInstance(NullableListValueJSONObjectScalarTypeResolver::class);
            $this->nullableListValueJSONObjectScalarTypeResolver = $nullableListValueJSONObjectScalarTypeResolver;
        }
        return $this->nullableListValueJSONObjectScalarTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to set entries on an entity', 'meta-mutations');
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
            [
                MutationInputProperties::ENTRIES => $this->getNullableListValueJSONObjectScalarTypeResolver(),
            ]
        );
    }

    abstract protected function addIDInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the entity', 'meta-mutations'),
            MutationInputProperties::ENTRIES => $this->__('The meta entries', 'meta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID,
            MutationInputProperties::ENTRIES
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
