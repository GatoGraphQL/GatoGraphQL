<?php
use PoP\Hooks\Facades\HooksAPIFacade;

 
class PoP_CDN_UserState_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:hasParamValues',
            array($this, 'getRejectedParamvalues')
        );
    }

    public function getRejectedParamvalues($paramvalues)
    {
        
        // Reject the CDN if loading the user state
        $paramvalues[] = array(
            GD_URLPARAM_ACTIONS,
            POP_ACTION_LOADUSERSTATE
        );
        
        return $paramvalues;
    }
}

/**
 * Initialize
 */
new PoP_CDN_UserState_Hooks();
