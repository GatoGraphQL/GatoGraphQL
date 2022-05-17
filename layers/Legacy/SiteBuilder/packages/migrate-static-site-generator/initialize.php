<?php
$class = (new class() extends \PoP\Root\Module\AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [];
    }

    public function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize code
        require_once 'migrate/pop-static-site-generator.php';
    }
});
\PoP\Root\StateManagers\ModuleManager::register(get_class($class));
$class::initialize();
