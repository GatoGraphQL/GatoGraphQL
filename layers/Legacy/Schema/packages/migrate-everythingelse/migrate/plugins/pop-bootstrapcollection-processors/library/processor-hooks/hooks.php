<?php

class PoP_CoreProcessors_Bootstrap_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonControls:modules:share',
            $this->getShareSubmodules(...),
            0,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $this->getDropdownSubmodules(...),
            0,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            $this->getSocialmediaSubmodules(...),
            0,
            2
        );
    }

    public function getSocialmediaSubmodules($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA], $subComponents);
                array_splice($subComponents, $pos, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA]));
                array_splice($subComponents, $pos+1, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA]));
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_USERSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA], $subComponents);
                array_splice($subComponents, $pos, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_EMBED_SOCIALMEDIA]));
                array_splice($subComponents, $pos+1, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_API_SOCIALMEDIA]));
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_TAGSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA], $subComponents);
                array_splice($subComponents, $pos, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA]));
                array_splice($subComponents, $pos+1, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA]));
                break;
        }
        
        return $subComponents;
    }

    public function getDropdownSubmodules($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subComponents);
                array_splice($subComponents, $pos, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN]));
                array_splice($subComponents, $pos+1, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN]));
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subComponents);
                array_splice($subComponents, $pos, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_EMBED_PREVIEWDROPDOWN]));
                array_splice($subComponents, $pos+1, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_API_PREVIEWDROPDOWN]));
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subComponents);
                array_splice($subComponents, $pos, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN]));
                array_splice($subComponents, $pos+1, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN]));
                break;
        }
        
        return $subComponents;
    }

    public function getShareSubmodules($subComponents, array $component)
    {

        // Insert before/after the Print button
        array_search([PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_PRINT], $subComponents);
        array_splice($subComponents, $pos, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_EMBED]));
        array_splice($subComponents, $pos+1, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_API]));

        if ($component == [PoP_Module_Processor_DropdownButtonControls::class, PoP_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE]) {
            array_splice($subComponents, $pos, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_COPYSEARCHURL]));
        }
        return $subComponents;
    }
}

/**
 * Initialization
 */
new PoP_CoreProcessors_Bootstrap_Hooks();
