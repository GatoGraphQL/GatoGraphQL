<?php
use PoP\ConfigurationComponentModel\ModuleDecoratorProcessors\ModuleDecoratorProcessorManagerTrait;

class PoP_ResourceModuleDecoratorProcessorManager
{
    use ModuleDecoratorProcessorManagerTrait;
}

/**
 * Initialization
 */
global $pop_resourcemoduledecoratorprocessor_manager;
$pop_resourcemoduledecoratorprocessor_manager = new PoP_ResourceModuleDecoratorProcessorManager();
