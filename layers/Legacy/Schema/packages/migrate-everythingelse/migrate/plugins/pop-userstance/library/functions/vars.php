<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\App;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'popUserstanceModuleInstanceComponents');
function popUserstanceModuleInstanceComponents($components)
{

    // Add the origin, as it is needed to decide between blocks PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE and self::COMPONENT_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE
    // The difference is, is there parameter "tid"?
    if (\PoP\Root\App::getState(['routing', 'is-generic'])) {
        $route = \PoP\Root\App::getState('route');

        if ($route == POP_USERSTANCE_ROUTE_ADDOREDITSTANCE) {
            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
            $stancetarget_name = $componentprocessor_manager->getProcessor([PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET])->getName([PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET]);
            $components[] = TranslationAPIFacade::getInstance()->__('stancetarget:', 'pop-userstance').(App::getRequest()->query->has($stancetarget_name) ? 'singlepost' : 'general');
        }
    }

    return $components;
}
