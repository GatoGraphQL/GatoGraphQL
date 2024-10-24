<?php

declare(strict_types=1);

namespace PoPIncludes\GatoGraphQL;

class SchemaEditingAccessCapabilities {

    public static function registerGatoGraphQLSchemaEditingAccessCapabilities(
        string $file,
        string $capability
    ): void {
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
