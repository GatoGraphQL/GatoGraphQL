<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;

interface TaxonomyTermObjectTypeResolverPickerInterface extends ObjectTypeResolverPickerInterface
{
    /**
     * @return string[]
     */
    public function getTaxonomyTermTaxonomies(): array;
}
