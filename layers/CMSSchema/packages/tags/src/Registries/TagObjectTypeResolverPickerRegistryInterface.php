<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\Registries;

use PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerInterface;

interface TagObjectTypeResolverPickerRegistryInterface
{
    public function addTagObjectTypeResolverPicker(TagObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker): void;
    /**
     * @return TagObjectTypeResolverPickerInterface[]
     */
    public function getTagObjectTypeResolverPickers(): array;
}
