<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Exception\ComponentNotExistsException;

/**
 * Keep a reference to all Components
 */
class ModuleManager implements ModuleManagerInterface
{
    /**
     * The initialized modules, stored under their class
     *
     * @var array<string,ModuleInterface>
     */
    protected array $modules = [];

    /**
     * Register and initialize a module
     */
    public function register(string $moduleClass): ModuleInterface
    {
        $module = new $moduleClass();
        $this->modules[$moduleClass] = $module;
        return $module;
    }

    /**
     * @throws ComponentNotExistsException If the class of the module does not exist or has not been initialized
     */
    public function getModule(string $moduleClass): ModuleInterface
    {
        if (!isset($this->modules[$moduleClass])) {
            throw new ComponentNotExistsException(\sprintf(
                'Module of class \'%s\' does not exist, or it has not been added for initialization',
                $moduleClass
            ));
        }
        return $this->modules[$moduleClass];
    }

    /**
     * Configure modules
     */
    public function configureComponents(): void
    {
        foreach ($this->modules as $module) {
            if (!$module->isEnabled()) {
                continue;
            }
            $module->configure();
        }
    }

    /**
     * Boot all modules
     */
    public function bootSystem(): void
    {
        foreach ($this->modules as $module) {
            if (!$module->isEnabled()) {
                continue;
            }
            $module->bootSystem();
        }
    }

    /**
     * Boot all modules
     */
    public function moduleLoaded(): void
    {
        foreach ($this->modules as $module) {
            if (!$module->isEnabled()) {
                continue;
            }
            $module->moduleLoaded();
        }
    }

    /**
     * Boot all modules
     */
    public function boot(): void
    {
        foreach ($this->modules as $module) {
            if (!$module->isEnabled()) {
                continue;
            }
            $module->boot();
        }
    }

    /**
     * Boot all modules
     */
    public function afterBoot(): void
    {
        foreach ($this->modules as $module) {
            if (!$module->isEnabled()) {
                continue;
            }
            $module->afterBoot();
        }
    }
}
