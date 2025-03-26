<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\Constants\MutationInputProperties;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\ListValueJSONObjectScalarTypeResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

abstract class AbstractSetTaxonomyTermMetaInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements SetTaxonomyTermMetaInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?ListValueJSONObjectScalarTypeResolver $listValueJSONObjectScalarTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getListValueJSONObjectScalarTypeResolver(): ListValueJSONObjectScalarTypeResolver
    {
        if ($this->listValueJSONObjectScalarTypeResolver === null) {
            /** @var ListValueJSONObjectScalarTypeResolver */
            $listValueJSONObjectScalarTypeResolver = $this->instanceManager->getInstance(ListValueJSONObjectScalarTypeResolver::class);
            $this->listValueJSONObjectScalarTypeResolver = $listValueJSONObjectScalarTypeResolver;
        }
        return $this->listValueJSONObjectScalarTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete a taxonomy term\'s meta entry', 'taxonomymeta-mutations');
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
                MutationInputProperties::ENTRIES => $this->getListValueJSONObjectScalarTypeResolver(),
            ]
        );
    }

    abstract protected function addIDInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the taxonomy term', 'taxonomymeta-mutations'),
            MutationInputProperties::ENTRIES => $this->__('The meta entries', 'taxonomymeta-mutations'),
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
