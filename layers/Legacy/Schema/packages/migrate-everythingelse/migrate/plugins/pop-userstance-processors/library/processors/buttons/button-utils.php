<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class UserStance_Module_Processor_ButtonUtils
{
    public static function getSinglepostAddstanceTitle()
    {
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing-state']['queried-object-id'];
    
        // Allow Events to have a different title
        return HooksAPIFacade::getInstance()->applyFilters(
            'UserStance_Module_Processor_ButtonUtils:singlepost:title',
            TranslationAPIFacade::getInstance()->__('After reading this information', 'pop-userstance-processors'),
            $post_id
        );
    }

    public static function getFullviewAddstanceTitle()
    {

        // Allow Events to have a different title
        return HooksAPIFacade::getInstance()->applyFilters(
            'UserStance_Module_Processor_ButtonUtils:fullview:title',
            TranslationAPIFacade::getInstance()->__('After reading this information', 'pop-userstance-processors')
        );
    }
}
