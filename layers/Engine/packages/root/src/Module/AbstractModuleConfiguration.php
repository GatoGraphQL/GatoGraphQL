<?php

declare(strict_types=1);

namespace PoP\Root\Module;

use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractModuleConfiguration implements ModuleConfigurationInterface
{
    /**
     * @param array<string,mixed> $configuration
     */
    final public function __construct(
        protected array $configuration
    ) {
    }

    public function hasConfigurationValue(string $envVariable): bool
    {
        return array_key_exists($envVariable, $this->configuration);
    }

    public function getConfigurationValue(string $envVariable): mixed
    {
        return $this->configuration[$envVariable] ?? null;
    }

    protected function retrieveConfigurationValueOrUseDefault(
        string $envVariable,
        mixed $defaultValue,
        ?callable $callback = null
    ): mixed {
        // Initialized from configuration? Then use that one directly.
        if ($this->hasConfigurationValue($envVariable)) {
            return $this->getConfigurationValue($envVariable);
        }

        /**
         * Otherwise, initialize from environment.
         * First set the default value, for if there's no env var defined.
         */
        $this->configuration[$envVariable] = $defaultValue;

        /**
         * Get the value from the environment, converting it
         * to the appropriate type via a callback function.
         */
        $envValue = \getenv($envVariable);
        if ($envValue !== false) {
            // Modify the type of the variable, from string to bool/int/array
            $this->configuration[$envVariable] = $callback !== null ? $callback($envValue) : $envValue;
        }

        if (!$this->enableHook($envVariable)) {
            return $this->configuration[$envVariable];
        }

        /**
         * Allow multiple classes for a StandaloneModule to also
         * get the configuration from the upstream Module
         */
        $classes = $this->getModuleClasses();
        foreach ($classes as $class) {
            $hookName = ModuleConfigurationHelpers::getHookName(
                $class,
                $envVariable
            );
            $this->configuration[$envVariable] = App::applyFilters(
                $hookName,
                $this->configuration[$envVariable],
                $class,
                $envVariable
            );
        }

        return $this->configuration[$envVariable];
    }

    protected function enableHook(string $envVariable): bool
    {
        return true;
    }

    /**
     * Package's Module class, of type ModuleInterface.
     * By standard, it is "NamespaceOwner\Project\Module::class"
     *
     * @phpstan-return array<class-string<ModuleInterface>>
     */
    protected function getModuleClasses(): array
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        /** @var array<class-string<ModuleInterface>> */
        return [
            $classNamespace . '\\Module',
        ];
    }
}
