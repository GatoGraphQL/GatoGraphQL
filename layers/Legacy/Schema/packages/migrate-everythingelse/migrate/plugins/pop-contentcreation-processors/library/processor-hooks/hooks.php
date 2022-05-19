<?php

class PoP_GenericFormsProcessors_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $this->getDropdownSubcomponents(...),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            $this->getSocialmediaSubcomponents(...),
            10,
            2
        );
    }

    public function getDropdownSubcomponents($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $subComponents[] = [PoP_ContentCreation_Module_Processor_PostViewComponentButtons::class, PoP_ContentCreation_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN];
                break;
        }
        
        return $subComponents;
    }

    public function getSocialmediaSubcomponents($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS:
                $subComponents[] = [PoP_ContentCreation_Module_Processor_PostViewComponentButtons::class, PoP_ContentCreation_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA];
                break;
        }
        
        return $subComponents;
    }
}

/**
 * Initialization
 */
new PoP_GenericFormsProcessors_Hooks();
