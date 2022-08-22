<?php
$class = (new class() extends \PoP\Root\Module\AbstractModule
{
    /**
     * @return array<class-string<\PoP\Root\Module\ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [];
    }

    public function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize code
        require_once 'migrate/pop-commentmeta.php';
    }
});
\PoP\Root\StateManagers\ModuleManager::register(get_class($class));
$class::initialize();
