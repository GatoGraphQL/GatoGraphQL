<?php

declare(strict_types=1);

namespace PoP\API\Conditional\AccessControl;

use PoP\API\Component;
use PoP\AccessControl\ComponentConfiguration;
use PoP\Root\Component\YAMLServicesTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\API\DirectiveResolvers\ConditionalOnEnvironment\SchemaNoCacheCacheControlDirectiveResolver;

/**
 * Initialize component
 */
class ConditionalComponent
{
    use YAMLServicesTrait;

    public static function initialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        self::initYAMLServices(Component::$COMPONENT_DIR, '/Conditional/AccessControl');
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        // Initialize classes
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
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
        if (
            ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode() ||
            ComponentConfiguration::usePrivateSchemaMode()
        ) {
            SchemaNoCacheCacheControlDirectiveResolver::attach(AttachableExtensionGroups::DIRECTIVERESOLVERS);
        }
    }
}
