<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginManagement;

interface PluginOptionsFormHandlerInterface
{
    /**
     * If we are in options.php, already set the new slugs in the hook,
     * so that the EndpointHandler's `addRewriteEndpoints` (executed on `init`)
     * adds the rewrite with the new slug, which will be persisted on
     * flushing the rewrite rules
     *
     * Hidden input "form-origin" is used to only execute for this plugin,
     * since options.php is used everywhere, including WP core and other plugins.
     * Otherwise, it may thrown an exception!
     */
    public function maybeOverrideValueFromForm(
        mixed $value,
        string $module,
        string $option,
    ): mixed;

    /**
     * Process the "URL path" option values
     */
    public function getURLPathSettingValue(
        string $value,
        string $module,
        string $option
    ): string;

    /**
     * Process the "URL base path" option values
     */
    public function getCPTPermalinkBasePathSettingValue(
        string $value,
        string $module,
        string $option
    ): string;
}
