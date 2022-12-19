<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\Registries;

use PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface;

interface CategoryObjectTypeResolverPickerRegistryInterface
{
    public function addCategoryObjectTypeResolverPicker(CategoryObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker): void;
    /**
     * @return CategoryObjectTypeResolverPickerInterface[]
     */
    public function getCategoryObjectTypeResolverPickers(): array;
}
