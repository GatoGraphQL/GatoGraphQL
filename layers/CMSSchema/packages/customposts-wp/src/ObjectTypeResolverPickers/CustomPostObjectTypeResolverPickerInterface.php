<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface as UpstreamCustomPostObjectTypeResolverPickerInterface;

interface CustomPostObjectTypeResolverPickerInterface extends UpstreamCustomPostObjectTypeResolverPickerInterface
{
    /**
     * Maybe cast the object of type `WP_Post` returned by function `get_posts`, to a different object type
     *
     * @param array<int|string,object> $customPosts An array with "key" the ID, "value" the object
     * @return array<int|string,object>
     */
    public function maybeCastCustomPosts(array $customPosts): array;
}
