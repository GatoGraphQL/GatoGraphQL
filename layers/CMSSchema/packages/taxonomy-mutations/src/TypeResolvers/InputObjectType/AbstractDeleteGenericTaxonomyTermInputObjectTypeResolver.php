<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractDeleteGenericTaxonomyTermInputObjectTypeResolver extends AbstractDeleteTaxonomyTermInputObjectTypeResolver implements DeleteGenericTaxonomyTermInputObjectTypeResolverInterface
{
    use MutateGenericTaxonomyTermInputObjectTypeResolverTrait;

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            $this->getGenericTaxonomyTermInputFieldNameTypeResolvers()
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return $this->getGenericTaxonomyTermInputFieldDescription($inputFieldName) ?? parent::getInputFieldDescription($inputFieldName);
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return $this->getGenericTaxonomyTermInputFieldTypeModifiers($inputFieldName) ?? parent::getInputFieldTypeModifiers($inputFieldName);
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }
}
