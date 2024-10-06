<?php

declare(strict_types=1);

require_once __DIR__ . '/capabilities.php';

/**
 * Make sure this function is not declared more than once
 * (eg: if for some reason the website has both the Extension
 * and the Bundle installed).
 */
if (!function_exists('registerGatoGraphQLSchemaEditingAccessCapabilities')) {
    function registerGatoGraphQLSchemaEditingAccessCapabilities(string $file): void
    {
        $capability = constant('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA');

        /**
         * This method cannot be invoked from within "plugins_loaded",
         * then place it here.
         *
         * @see https://developer.wordpress.org/reference/functions/register_activation_hook/#process-flow
         */
        register_activation_hook(
            $file,
            /**
             * Register custom capabilities
             */
            function () use ($capability): void {
                $role = get_role('administrator');
                $role->add_cap($capability);
            }
        );

        /**
         * For consistency, also place the deregistration here
         */
        register_deactivation_hook(
            $file,
            /**
             * Unregister custom capabilities
             */
            function () use ($capability): void {
                $role = get_role('administrator');
                $role->remove_cap($capability);
            }
        );
    }
}
