<?php
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;

class PoP_UserStateModuleDecoratorProcessorHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
            AbstractModuleProcessor::HOOK_ADD_HEADDATASETMODULE_DATAPROPERTIES,
            array($this, 'addHeaddatasetmoduleDataProperties'),
            10,
            4
        );
    }

    public function addHeaddatasetmoduleDataProperties($ret_in_array, array $module, $props_in_array, $processor)
    {
        $processoruserstatedecorator = PoP_UserStateModuleDecoratorProcessorManagerFactory::getInstance()->getProcessorDecorator($processor);

        // If the block requires user state, but the current page does not, then make the block be lazy-loaded
        // Only when first loading the page.
        // Needed to load the Add your Stance block (which requires state) on the homepage (which requires not)
        $props = &$props_in_array[0];
        if ($processoruserstatedecorator->requiresUserState($module, $props) && !PoP_UserState_Utils::currentRouteRequiresUserState()) {
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
