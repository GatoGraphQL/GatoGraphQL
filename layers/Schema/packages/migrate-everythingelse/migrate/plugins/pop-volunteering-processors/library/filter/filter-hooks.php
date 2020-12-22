<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPThemeWassupCommonPages_GenericForms_DataLoad_FilterHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'FilterInners:inputmodules',
            array(PoP_VolunteeringProcessors_FilterUtils::class, 'maybeAddVolunteerFilterinput'),
            10,
            2
        );
    }
}
    
/**
 * Initialize
 */
new PoPThemeWassupCommonPages_GenericForms_DataLoad_FilterHooks();
