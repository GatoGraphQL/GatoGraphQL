<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\PostTypes;

interface CustomPostTypeInterface
{
    /**
     * Unregister the custom post type
     */
    public function unregisterPostType(): void;
}
