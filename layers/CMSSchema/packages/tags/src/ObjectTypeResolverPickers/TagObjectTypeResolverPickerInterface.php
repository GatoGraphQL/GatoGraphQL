<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;

interface TagObjectTypeResolverPickerInterface extends ObjectTypeResolverPickerInterface
{
    /**
     * @return string[]
     */
    public function getTagTaxonomies(): array;
}
