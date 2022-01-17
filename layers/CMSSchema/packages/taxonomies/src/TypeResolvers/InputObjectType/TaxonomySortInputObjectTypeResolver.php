<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\Constants\TaxonomyOrderBy;
use PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class TaxonomySortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?TaxonomyOrderByEnumTypeResolver $taxonomySortByEnumTypeResolver = null;

    final public function setTaxonomyOrderByEnumTypeResolver(TaxonomyOrderByEnumTypeResolver $taxonomySortByEnumTypeResolver): void
    {
        $this->taxonomySortByEnumTypeResolver = $taxonomySortByEnumTypeResolver;
    }
    final protected function getTaxonomyOrderByEnumTypeResolver(): TaxonomyOrderByEnumTypeResolver
    {
        return $this->taxonomySortByEnumTypeResolver ??= $this->instanceManager->getInstance(TaxonomyOrderByEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'TaxonomySortInput';
    }

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
