<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;

interface CustomPostTypeResolverPickerInterface extends ObjectTypeResolverPickerInterface
{
    /**
     * Get the post type of the Type (eg: Post is "post", Media is "attachment", etc)
     */
    public function getCustomPostType(): string;
}
