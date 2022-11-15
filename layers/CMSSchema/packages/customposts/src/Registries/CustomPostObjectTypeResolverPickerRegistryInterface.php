<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Registries;

use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;

interface CustomPostObjectTypeResolverPickerRegistryInterface
{
    public function addCustomPostObjectTypeResolverPicker(CustomPostObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker): void;
    /**
     * @return CustomPostObjectTypeResolverPickerInterface[]
     */
    public function getCustomPostObjectTypeResolverPickers(): array;
}
