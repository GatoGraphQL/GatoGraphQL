<?php
use PoP\ConfigurationComponentModel\ModuleDecoratorProcessors\ModuleDecoratorProcessorManagerTrait;

class PoP_DynamicDataModuleDecoratorProcessorManager
{
    use ModuleDecoratorProcessorManagerTrait;
}

/**
 * Initialization
 */
global $pop_component_processordynamicdatadecorator_manager;
$pop_component_processordynamicdatadecorator_manager = new PoP_DynamicDataModuleDecoratorProcessorManager();
