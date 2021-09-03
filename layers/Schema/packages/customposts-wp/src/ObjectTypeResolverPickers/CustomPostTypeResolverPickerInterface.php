<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\ObjectTypeResolverPickers;

interface CustomPostTypeResolverPickerInterface extends \PoPSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostTypeResolverPickerInterface
{
    /**
     * Maybe cast the object of type `WP_Post` returned by function `get_posts`, to a different object type
     *
     * @param array $customPosts An array with "key" the ID, "value" the object
     */
    public function maybeCastCustomPosts(array $customPosts): array;
}
