<?php
use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;

class PoP_UserStateModuleDecoratorProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            AbstractComponentProcessor::HOOK_ADD_HEADDATASETCOMPONENT_DATAPROPERTIES,
            $this->addHeaddatasetcomponentDataProperties(...),
            10,
            4
        );
    }

    public function addHeaddatasetcomponentDataProperties($ret_in_array, \PoP\ComponentModel\Component\Component $component, $props_in_array, $processor)
    {
        $processoruserstatedecorator = PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->getProcessorDecorator($processor);

        // If the block requires user state, but the current page does not, then make the block be lazy-loaded
        // Only when first loading the page.
        // Needed to load the Add your Stance block (which requires state) on the homepage (which requires not)
        $props = &$props_in_array[0];
        if ($processoruserstatedecorator->requiresUserState($component, $props) && !PoP_UserState_Utils::currentRouteRequiresUserState()) {
            $ret = &$ret_in_array[0];
            $ret[DataloadingConstants::SKIPDATALOAD] = true;
            $ret[GD_DATALOAD_USERSTATEDATALOAD] = true;
        }
    }
}


/**
 * Initialization
 */
new PoP_UserStateModuleDecoratorProcessorHooks();
