<?php
$class = (new class() extends \PoP\Root\Module\AbstractModule
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [];
    }

    /**
     * Boot component
     */
    public function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize code
        require_once 'migrate/pop-static-site-generator.php';
    }
});
\PoP\Root\StateManagers\ModuleManager::register(get_class($class));
$class::initialize();
