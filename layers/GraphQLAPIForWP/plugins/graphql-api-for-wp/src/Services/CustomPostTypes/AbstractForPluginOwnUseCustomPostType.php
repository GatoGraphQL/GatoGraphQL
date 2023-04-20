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

    /**
     * This plugin's Configuration CPTs (eg: SchemaConfig, 
     * ACLs, CCLs, etc) are to configure data,
     * and not to be directly accessible by themselves.
     *
     * Then, do not make them public, but still allow to access them.
     *
     * This way, executing query:
     * 
     *   { customPosts(customPostTypes:["graphql-schemaconfig"]) }
     * 
     * ...will fail, and we execute instead:
     * 
     *   { schemaConfigurations }
     *
     * which can be @validated
     */
    protected function isPubliclyQueryable(): bool
    {
        return true;
    }
}
