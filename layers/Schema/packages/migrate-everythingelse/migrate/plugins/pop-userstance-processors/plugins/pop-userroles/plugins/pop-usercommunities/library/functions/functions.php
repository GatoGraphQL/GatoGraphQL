<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

// Execute after callable function has been defined in poptheme-wassup
HooksAPIFacade::getInstance()->addAction(
    'plugins_loaded',
    function () {
        HooksAPIFacade::getInstance()->addFilter('UserStance_Module_Processor_CustomCarouselControls:authorstances:titlelink', 'gdUreAddSourceParamPagesections');
    },
    1501
);

HooksAPIFacade::getInstance()->addFilter('UserStance_Module_Processor_CustomCarouselControls:authorstances:title', 'gdUserstanceUreTitlemembers');
function gdUserstanceUreTitlemembers($title)
{
    $vars = ApplicationState::getVars();
    $author = $vars['routing-state']['queried-object-id'];
    if (gdUreIsCommunity($author)) {
        $vars = ApplicationState::getVars();
        if (isset($vars['source']) && $vars['source'] == GD_URLPARAM_URECONTENTSOURCE_COMMUNITY) {
            $title .= TranslationAPIFacade::getInstance()->__(' + Members', 'pop-userstance-processors');
        }
    }

    return $title;
}
