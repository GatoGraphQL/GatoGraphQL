<?php
$class = (new class() extends \PoP\Root\Component\AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [];
    }

    /**
     * Boot component
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize code
        require_once 'migrate/pop-taxonomymeta.php';
    }
});
\PoP\Root\Managers\ComponentManager::register(get_class($class));
$class::initialize();
