<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

interface CustomPostTypeInterface
{
    public function getCustomPostType(): string;
    /**
     * Unregister the custom post type
     */
    public function unregisterCustomPostType(): void;
    /**
     * Register the custom post type
     */
    public function registerCustomPostType(): void;
}
