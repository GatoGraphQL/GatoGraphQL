<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers;

trait NoCastCustomPostTypeResolverPickerTrait
{
    /**
     * Do not cast the object of type `WP_Post` returned by function `get_posts`, since it already satisfies this Type too (eg: locationPost)
     *
     * @param array<int|string,object> $customPosts An array with "key" the ID, "value" the object
     * @return array<int|string,object>
     */
    public function maybeCastCustomPosts(array $customPosts): array
    {
        return $customPosts;
    }
}
