<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;


class PoP_CDN_UserState_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:hasParamValues',
            array($this, 'getRejectedParamvalues')
        );
    }

    public function getRejectedParamvalues($paramvalues)
    {

        // Reject the CDN if loading the user state
        $paramvalues[] = array(
            \PoP\ComponentModel\Constants\Params::ACTIONS,
            POP_ACTION_LOADUSERSTATE
        );

        return $paramvalues;
    }
}

/**
 * Initialize
 */
new PoP_CDN_UserState_Hooks();
