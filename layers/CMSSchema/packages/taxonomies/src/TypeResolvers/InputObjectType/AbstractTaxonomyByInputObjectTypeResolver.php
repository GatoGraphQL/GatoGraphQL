<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput;

abstract class AbstractTaxonomyByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IncludeFilterInput $includeFilterInput = null;
    private ?SlugFilterInput $slugFilterInput = null;

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
    final public function setIncludeFilterInput(IncludeFilterInput $includeFilterInput): void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    final protected function getIncludeFilterInput(): IncludeFilterInput
    {
        return $this->includeFilterInput ??= $this->instanceManager->getInstance(IncludeFilterInput::class);
    }
    final public function setSlugFilterInput(SlugFilterInput $slugFilterInput): void
    {
        $this->slugFilterInput = $slugFilterInput;
    }
    final protected function getSlugFilterInput(): SlugFilterInput
    {
        return $this->slugFilterInput ??= $this->instanceManager->getInstance(SlugFilterInput::class);
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
     * @return array<string,\PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'id' => $this->getIDScalarTypeResolver(),
            'slug' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by taxonomy ID', 'taxonomies'),
            'slug' => $this->__('Query by taxonomy slug', 'taxonomies'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'id' => $this->getIncludeFilterInput(),
            'slug' => $this->getSlugFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
