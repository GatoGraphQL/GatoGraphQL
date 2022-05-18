<?php

class PoPTheme_Wassup_GenericForms_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules',
            $this->singleComponents(...)
        );
    }

    public function singleComponents($layouts)
    {
        array_splice(
            $layouts, 
            array_search(
                [PoP_Module_Processor_SocialMediaPostWrappers::class, PoP_Module_Processor_SocialMediaPostWrappers::COMPONENT_POSTSOCIALMEDIA_POSTWRAPPER], 
                $layouts
            ), 
            0, 
            array(
                [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG]
            )
        );
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_GenericForms_LayoutHooks();
