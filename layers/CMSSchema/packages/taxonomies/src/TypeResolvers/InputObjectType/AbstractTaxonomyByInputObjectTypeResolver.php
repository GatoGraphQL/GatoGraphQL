<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput;
use PoPCMSSchema\Taxonomies\Constants\InputProperties;

abstract class AbstractTaxonomyByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IncludeFilterInput $includeFilterInput = null;
    private ?SlugFilterInput $slugFilterInput = null;

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
    final protected function getIncludeFilterInput(): IncludeFilterInput
    {
        if ($this->includeFilterInput === null) {
            /** @var IncludeFilterInput */
            $includeFilterInput = $this->instanceManager->getInstance(IncludeFilterInput::class);
            $this->includeFilterInput = $includeFilterInput;
        }
        return $this->includeFilterInput;
    }
    final protected function getSlugFilterInput(): SlugFilterInput
    {
        if ($this->slugFilterInput === null) {
            /** @var SlugFilterInput */
            $slugFilterInput = $this->instanceManager->getInstance(SlugFilterInput::class);
            $this->slugFilterInput = $slugFilterInput;
        }
        return $this->slugFilterInput;
    }

    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->__('Oneof input to specify the property and data to fetch %s', 'customposts'),
            $this->getTypeDescriptionTaxonomyEntity()
        );
    }

    protected function getTypeDescriptionTaxonomyEntity(): string
    {
        return $this->__('a taxonomy', 'customposts');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            InputProperties::ID => $this->getIDScalarTypeResolver(),
            InputProperties::SLUG => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->__('Query by taxonomy ID', 'taxonomies'),
            InputProperties::SLUG => $this->__('Query by taxonomy slug', 'taxonomies'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->getIncludeFilterInput(),
            InputProperties::SLUG => $this->getSlugFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
