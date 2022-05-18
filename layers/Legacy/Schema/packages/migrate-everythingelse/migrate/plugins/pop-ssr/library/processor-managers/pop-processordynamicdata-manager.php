<?php
use PoP\ComponentModel\ModuleDecoratorProcessors\ModuleDecoratorProcessorManagerTrait;

class PoP_DynamicDataModuleDecoratorProcessorManager
{
    use ModuleDecoratorProcessorManagerTrait;
}

/**
 * Initialization
 */
global $pop_componentVariation_processordynamicdatadecorator_manager;
$pop_componentVariation_processordynamicdatadecorator_manager = new PoP_DynamicDataModuleDecoratorProcessorManager();
