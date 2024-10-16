<?php

declare(strict_types=1);

/**
 * Make sure this function is not declared more than once
 */
if (!function_exists('checkGatoGraphQLMemoryRequirements')) {
    /**
     * Validate that there is enough memory to run the plugin.
     *
     * > Note that to have no memory limit, set this directive to -1.
     *
     * @see https://www.php.net/manual/en/ini.core.php#ini.sect.resource-limits
     */
    function checkGatoGraphQLMemoryRequirements(string $pluginName): bool
    {
        $phpMemoryLimit = \ini_get('memory_limit');
        $phpMemoryLimitInBytes = \wp_convert_hr_to_bytes($phpMemoryLimit);
        if ($phpMemoryLimitInBytes !== -1) {
            // Minimum: 64MB
            $minRequiredPHPMemoryLimit = '64M';
            $minRequiredPHPMemoryLimitInBytes = \wp_convert_hr_to_bytes($minRequiredPHPMemoryLimit);
            if ($phpMemoryLimitInBytes < $minRequiredPHPMemoryLimitInBytes) {
                \add_action('admin_notices', function () use ($minRequiredPHPMemoryLimit, $phpMemoryLimit, $pluginName) {
                    printf(
                        '<div class="notice notice-error"><p>%s</p></div>',
                        sprintf(
                            __('Plugin <strong>%1$s</strong> requires at least <strong>%2$s</strong> of memory, however the server\'s PHP memory limit is set to <strong>%3$s</strong>. Please increase the memory limit to load %1$s.', 'gatographql'),
                            $pluginName,
                            $minRequiredPHPMemoryLimit,
                            $phpMemoryLimit
                        )
                    );
                });
                return false;
            }
        }
        return true;
    }
}