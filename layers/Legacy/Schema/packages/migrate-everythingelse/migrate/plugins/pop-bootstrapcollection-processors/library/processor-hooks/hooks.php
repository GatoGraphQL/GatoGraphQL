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

    public function getSocialmediaSubmodules($submodules, array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_SOCIALMEDIA], $submodules);
                array_splice($submodules, $pos, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA]));
                array_splice($submodules, $pos+1, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA]));
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_USERSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_SOCIALMEDIA], $submodules);
                array_splice($submodules, $pos, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_EMBED_SOCIALMEDIA]));
                array_splice($submodules, $pos+1, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_API_SOCIALMEDIA]));
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_TAGSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_SOCIALMEDIA], $submodules);
                array_splice($submodules, $pos, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA]));
                array_splice($submodules, $pos+1, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA]));
                break;
        }
        
        return $submodules;
    }

    public function getDropdownSubmodules($submodules, array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $submodules);
                array_splice($submodules, $pos, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN]));
                array_splice($submodules, $pos+1, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN]));
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $submodules);
                array_splice($submodules, $pos, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_EMBED_PREVIEWDROPDOWN]));
                array_splice($submodules, $pos+1, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_API_PREVIEWDROPDOWN]));
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $submodules);
                array_splice($submodules, $pos, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN]));
                array_splice($submodules, $pos+1, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN]));
                break;
        }
        
        return $submodules;
    }

    public function getShareSubmodules($submodules, array $componentVariation)
    {

        // Insert before/after the Print button
        array_search([PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_PRINT], $submodules);
        array_splice($submodules, $pos, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_EMBED]));
        array_splice($submodules, $pos+1, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_API]));

        if ($componentVariation == [PoP_Module_Processor_DropdownButtonControls::class, PoP_Module_Processor_DropdownButtonControls::MODULE_DROPDOWNBUTTONCONTROL_RESULTSSHARE]) {
            array_splice($submodules, $pos, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_COPYSEARCHURL]));
        }
        return $submodules;
    }
}

/**
 * Initialization
 */
new PoP_CoreProcessors_Bootstrap_Hooks();
