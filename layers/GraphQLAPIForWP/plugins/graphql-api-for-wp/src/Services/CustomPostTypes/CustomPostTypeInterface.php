<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

interface CustomPostTypeInterface
{
    /**
     * Unregister the custom post type
     */
    public function unregister(): void;
    /**
     * Register the custom post type
     */
    public function register(): void;
}
