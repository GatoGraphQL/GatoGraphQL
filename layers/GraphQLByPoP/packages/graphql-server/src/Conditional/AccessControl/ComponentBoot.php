<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Conditional\AccessControl;

use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use GraphQLByPoP\GraphQLServer\DirectiveResolvers\ConditionalOnEnvironment\SchemaNoCacheCacheControlDirectiveResolver;

/**
 * Initialize component
 */
class ComponentBoot
{
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        // Initialize classes
        self::attachDynamicDirectiveResolvers();
    }

    /**
     * Attach directive resolvers based on environment variables
     *
     * @return void
     */
    protected static function attachDynamicDirectiveResolvers()
    {
        /**
         * If either constant `USE_PRIVATE_SCHEMA_MODE` or `ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE`
         * (which enables to define the private schema mode for a specific entry) is true,
         * then the schema (as obtained by querying the "__schema" field) is dynamic:
         * Fields will be available or not depending on the user being logged in or not
         * Then, the CacheControl for field "__schema" must be set to "no-cache"
         */
        if (AccessControlComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode() ||
            AccessControlComponentConfiguration::usePrivateSchemaMode()
        ) {
            SchemaNoCacheCacheControlDirectiveResolver::attach(AttachableExtensionGroups::DIRECTIVERESOLVERS);
        }
    }
}
