<?php

class PoPTheme_Wassup_GenericForms_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules',
            array($this, 'singleComponents')
        );
    }

    public function singleComponents($layouts)
    {
        array_splice(
            $layouts, 
            array_search(
                [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::MODULE_POSTSOCIALMEDIA_POSTWRAPPER], 
                $layouts
            ), 
            0, 
            array(
                [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG]
            )
        );
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_GenericForms_LayoutHooks();
