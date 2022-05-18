<?php
use PoP\ConfigurationComponentModel\ModuleDecoratorProcessors\ModuleDecoratorProcessorManagerTrait;

class PoP_UserStateModuleDecoratorProcessorManager
{
    use ModuleDecoratorProcessorManagerTrait;

    public function __construct()
    {
    	PoP_UserStateModuleDecoratorProcessorManagerFactory::setInstance($this);
    }
}

/**
 * Initialization
 */
new PoP_UserStateModuleDecoratorProcessorManager();
