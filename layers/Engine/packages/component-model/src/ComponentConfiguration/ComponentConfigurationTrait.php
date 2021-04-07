<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentConfiguration;

use PoP\Hooks\Facades\SystemHooksAPIFacade;

/**
 * Make the ComponentConfiguration be configurable
 */
trait ComponentConfigurationTrait
{
    protected static array $configuration = [];
    protected static array $initialized = [];

    public static function setConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }
    public static function hasConfigurationValue(string $option)
    {
        return isset(self::$configuration[$option]);
    }
    public static function getConfigurationValue(string $option)
    {
        return self::$configuration[$option];
    }

    protected static function maybeInitializeConfigurationValue(
        string $envVariable,
        &$selfProperty,
        $defaultValue = null,
        ?callable $callback = null,
        bool $useHook = true
    ): void {
        if (!isset(self::$initialized[$envVariable])) {
            self::$initialized[$envVariable] = true;

            $selfProperty = $defaultValue;
            // Initialize from configuration, environment or hook
            if (self::hasConfigurationValue($envVariable)) {
                // Priority: option has been set in the $configuration
                $selfProperty = self::getConfigurationValue($envVariable);
            } else {
                // Get the value from the environment function
                if (getenv($envVariable) !== false) {
                    $selfProperty = getenv($envVariable);
                    // Modify the type of the variable, from string to bool/int/array
                    if ($callback) {
                        $selfProperty = $callback($selfProperty);
                    }
                }
                // Allow to override the value with a hook
                if ($useHook) {
                    /**
                     * Important: it must use the Hooks service from the System Container,
                     * and not the (Application) Container, because ComponentConfiguration::foo()
                     * may be accessed when initializing (Application) container services
                     * in `Component.initialize()`, so it must already be available by then
                     */
                    $hooksAPI = SystemHooksAPIFacade::getInstance();
                    $class = \get_called_class();
                    $hookName = ComponentConfigurationHelpers::getHookName(
                        $class,
                        $envVariable
                    );
                    $selfProperty = $hooksAPI->applyFilters(
                        $hookName,
                        $selfProperty,
                        $class,
                        $envVariable
                    );
                }
            }
        }
    }
}
