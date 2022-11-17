<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Registries;

use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;

class CustomPostObjectTypeResolverPickerRegistry implements CustomPostObjectTypeResolverPickerRegistryInterface
{
    /**
     * @var CustomPostObjectTypeResolverPickerInterface[]
     */
    protected array $customPostObjectTypeResolverPickers = [];

    public function addCustomPostObjectTypeResolverPicker(CustomPostObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker): void
    {
        $this->customPostObjectTypeResolverPickers[] = $customPostObjectTypeResolverPicker;
    }

    /**
     * @return CustomPostObjectTypeResolverPickerInterface[]
     */
    public function getCustomPostObjectTypeResolverPickers(): array
    {
        return $this->customPostObjectTypeResolverPickers;
    }
}
