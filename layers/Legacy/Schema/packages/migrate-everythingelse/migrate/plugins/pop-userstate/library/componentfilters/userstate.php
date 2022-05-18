<?php

define('POP_MODULEFILTER_USERSTATE', 'userstate');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class PoP_ComponentFilter_UserState extends AbstractComponentFilter
{
    public function getName(): string
    {
        return POP_MODULEFILTER_USERSTATE;
    }

    /**
     * Exclude if it has no user state
     */
    public function excludeModule(array $componentVariation, array &$props): bool
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($componentVariation);
        $processoruserstate = PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->getProcessorDecorator($processor);
        return !$processoruserstate->requiresUserState($componentVariation, $props);
    }
}

/**
 * Initialization
 */
new PoP_ComponentFilter_UserState();
