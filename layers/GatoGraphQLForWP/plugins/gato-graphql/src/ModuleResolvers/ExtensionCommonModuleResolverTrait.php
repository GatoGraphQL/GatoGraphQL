<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use WP_Post;

use function get_posts;

trait ExtensionCommonModuleResolverTrait
{
    use CommonModuleResolverTrait;

    /**
     * @return WP_Post[]
     */
    protected function getSchemaEntityListCustomPosts(string $customPostType): array
    {
        return get_posts([
            'posts_per_page' => -1,
            'post_type' => $customPostType,
            'post_status' => 'publish',
        ]);
    }
}
