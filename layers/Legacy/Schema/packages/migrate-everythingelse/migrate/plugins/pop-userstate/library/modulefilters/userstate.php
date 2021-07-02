<?php

define('POP_MODULEFILTER_USERSTATE', 'userstate');

use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_ModuleFilter_UserState extends AbstractModuleFilter
{
    public function getName(): string
    {
        return POP_MODULEFILTER_USERSTATE;
    }

    public function excludeModule(array $module, array &$props): bool
    {

        // Exclude if it has no user state
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        $processoruserstate = PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->getProcessordecorator($processor);
        return !$processoruserstate->requiresUserState($module, $props);
    }
}

/**
 * Initialization
 */
new PoP_ModuleFilter_UserState();
