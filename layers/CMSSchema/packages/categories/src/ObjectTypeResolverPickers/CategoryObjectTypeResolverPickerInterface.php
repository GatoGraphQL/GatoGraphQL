<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;

interface CategoryObjectTypeResolverPickerInterface extends ObjectTypeResolverPickerInterface
{
    /**
     * @return string[]
     */
    public function getCategoryTaxonomies(): array;
}
