<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Taxonomies\Constants\TaxonomyOrderBy;
use PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class TaxonomySortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?TaxonomyOrderByEnumTypeResolver $taxonomySortByEnumTypeResolver = null;

    final protected function getTaxonomyOrderByEnumTypeResolver(): TaxonomyOrderByEnumTypeResolver
    {
        if ($this->taxonomySortByEnumTypeResolver === null) {
            /** @var TaxonomyOrderByEnumTypeResolver */
            $taxonomySortByEnumTypeResolver = $this->instanceManager->getInstance(TaxonomyOrderByEnumTypeResolver::class);
            $this->taxonomySortByEnumTypeResolver = $taxonomySortByEnumTypeResolver;
        }
        return $this->taxonomySortByEnumTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'TaxonomySortInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'by' => $this->getTaxonomyOrderByEnumTypeResolver(),
            ]
        );
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'by' => TaxonomyOrderBy::NAME,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
