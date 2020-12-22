<?php
use PoP\Hooks\Facades\HooksAPIFacade;

 
class PPPPoP_CDN_Hooks
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
        
        // Reject the CDN if viewing a preview post
        $paramvalues[] = array(
            POP_PARAMS_PREVIEW,
            true
        );
        $paramvalues[] = array(
            POP_PARAMS_PREVIEW,
            1
        );
        
        return $paramvalues;
    }
}

/**
 * Initialize
 */
new PPPPoP_CDN_Hooks();
