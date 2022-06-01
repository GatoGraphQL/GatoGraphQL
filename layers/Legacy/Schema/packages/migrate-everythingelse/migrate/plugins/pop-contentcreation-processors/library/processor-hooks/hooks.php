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

    /**
     * @param \PoP\ComponentModel\Component\Component[] $subcomponents
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getDropdownSubcomponents(array $subcomponents, \PoP\ComponentModel\Component\Component $component): array
    {
        switch ($component->name) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $subcomponents[] = [PoP_ContentCreation_Module_Processor_PostViewComponentButtons::class, PoP_ContentCreation_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN];
                break;
        }
        
        return $subcomponents;
    }

    public function getSocialmediaSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS:
                $subcomponents[] = [PoP_ContentCreation_Module_Processor_PostViewComponentButtons::class, PoP_ContentCreation_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA];
                break;
        }
        
        return $subcomponents;
    }
}

/**
 * Initialization
 */
new PoP_GenericFormsProcessors_Hooks();
