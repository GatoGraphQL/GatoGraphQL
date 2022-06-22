<?php

define('POP_COMPONENTFILTER_USERSTATE', 'userstate');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class PoP_ComponentFilter_UserState extends AbstractComponentFilter
{
    public function getName(): string
    {
        return POP_COMPONENTFILTER_USERSTATE;
    }

    /**
     * Exclude if it has no user state
     */
    public function excludeSubcomponent(\PoP\ComponentModel\Component\Component $component, array &$props): bool
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getComponentProcessor($component);
        $processoruserstate = PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->getProcessorDecorator($processor);
        return !$processoruserstate->requiresUserState($component, $props);
    }
}

/**
 * Initialization
 */
new PoP_ComponentFilter_UserState();
