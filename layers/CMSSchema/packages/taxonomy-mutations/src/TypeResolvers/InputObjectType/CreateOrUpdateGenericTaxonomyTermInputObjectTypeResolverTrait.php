<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait CreateOrUpdateGenericTaxonomyTermInputObjectTypeResolverTrait
{
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getGenericTaxonomyTermInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::TAXONOMY => $this->getGenericTaxonomyTermTaxonomyTypeResolver(),
        ];
    }

    abstract protected function getGenericTaxonomyTermTaxonomyTypeResolver(): TypeResolverInterface;

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
            MutationInputProperties::TAXONOMY => SchemaTypeModifiers::MANDATORY,
            default => null,
        };
    }
}
