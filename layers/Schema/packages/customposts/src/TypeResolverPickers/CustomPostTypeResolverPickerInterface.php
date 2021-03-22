<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolverPickers;

interface CustomPostTypeResolverPickerInterface
{
    /**
     * Get the post type of the Type (eg: Post is "post", Media is "attachment", etc)
     */
    public function getCustomPostType(): string;
}
