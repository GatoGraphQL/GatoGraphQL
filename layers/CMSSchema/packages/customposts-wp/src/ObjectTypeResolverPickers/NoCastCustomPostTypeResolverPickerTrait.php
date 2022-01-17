<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers;

trait NoCastCustomPostTypeResolverPickerTrait
{
    /**
     * Do not cast the object of type `WP_Post` returned by function `get_posts`, since it already satisfies this Type too (eg: locationPost)
     */
    public function maybeCastCustomPosts(array $customPosts): array
    {
        return $customPosts;
    }
}
