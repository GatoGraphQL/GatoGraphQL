<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

trait MutateGenericTaxonomyTermInputObjectTypeResolverTrait
{
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getGenericTaxonomyTermInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::TAXONOMY => $this->getGenericTaxonomyTermTaxonomyInputTypeResolver(),
        ];
    }

    abstract protected function getGenericTaxonomyTermTaxonomyInputTypeResolver(): InputTypeResolverInterface;

    public function getGenericTaxonomyTermInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::TAXONOMY => $this->__('The taxonomy', 'taxonomy-mutations'),
            default => null,
        };
    }

    public function getGenericTaxonomyTermInputFieldTypeModifiers(string $inputFieldName): ?int
    {
        return match ($inputFieldName) {
            MutationInputProperties::TAXONOMY => $this->isTaxonomyInputFieldMandatory()
                ? SchemaTypeModifiers::MANDATORY
                : SchemaTypeModifiers::NONE,
            default => null,
        };
    }

    abstract protected function isTaxonomyInputFieldMandatory(): bool;
}
