<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers;

interface NonGenericCustomPostObjectTypeResolverPickerInterface extends CustomPostObjectTypeResolverPickerInterface
{
    /**
     * Get the post type of the Type (eg: Post is "post", Media is "attachment", etc)
     */
    public function getCustomPostType(): string;
}
