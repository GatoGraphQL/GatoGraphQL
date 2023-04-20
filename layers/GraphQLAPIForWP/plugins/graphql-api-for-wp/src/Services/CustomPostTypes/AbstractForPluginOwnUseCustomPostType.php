<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

abstract class AbstractForPluginOwnUseCustomPostType extends AbstractCustomPostType
{
    /**
     * Indicate if to make the Custom Post Type public.
     * It is false for configuration CPTs (ACLs, CCLs,
     * Schema Configuration, etc) because this data is
     * private, must not be exposed.
     */
    protected function isPublic(): bool
    {
        return false;
    }

    protected function isPubliclyQueryable(): bool
    {
        return true;
    }
}
