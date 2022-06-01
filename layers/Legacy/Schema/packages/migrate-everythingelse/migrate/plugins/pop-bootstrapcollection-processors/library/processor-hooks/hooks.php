<?php

class PoP_CoreProcessors_Bootstrap_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonControls:components:share',
            $this->getShareSubcomponents(...),
            0,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            $this->getDropdownSubcomponents(...),
            0,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            $this->getSocialmediaSubcomponents(...),
            0,
            2
        );
    }

    public function getSocialmediaSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA], $subcomponents);
                array_splice($subcomponents, $pos, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_SOCIALMEDIA]));
                array_splice($subcomponents, $pos+1, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_SOCIALMEDIA]));
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_USERSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA], $subcomponents);
                array_splice($subcomponents, $pos, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_EMBED_SOCIALMEDIA]));
                array_splice($subcomponents, $pos+1, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_API_SOCIALMEDIA]));
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_TAGSECINTERACTIONS:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA], $subcomponents);
                array_splice($subcomponents, $pos, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_EMBED_SOCIALMEDIA]));
                array_splice($subcomponents, $pos+1, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_API_SOCIALMEDIA]));
                break;
        }
        
        return $subcomponents;
    }

    /**
     * @param \PoP\ComponentModel\Component\Component[] $subcomponents
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getDropdownSubcomponents(array $subcomponents, \PoP\ComponentModel\Component\Component $component): array
    {
        switch ($component->name) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subcomponents);
                array_splice($subcomponents, $pos, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_EMBED_PREVIEWDROPDOWN]));
                array_splice($subcomponents, $pos+1, 0, array([PoP_Module_Processor_PostViewComponentButtons::class, PoP_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_API_PREVIEWDROPDOWN]));
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subcomponents);
                array_splice($subcomponents, $pos, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_EMBED_PREVIEWDROPDOWN]));
                array_splice($subcomponents, $pos+1, 0, array([PoP_Module_Processor_UserViewComponentButtons::class, PoP_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_API_PREVIEWDROPDOWN]));
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                $pos = array_search([PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subcomponents);
                array_splice($subcomponents, $pos, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_EMBED_PREVIEWDROPDOWN]));
                array_splice($subcomponents, $pos+1, 0, array([PoP_Module_Processor_TagViewComponentButtons::class, PoP_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_API_PREVIEWDROPDOWN]));
                break;
        }
        
        return $subcomponents;
    }

    public function getShareSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {

        // Insert before/after the Print button
        array_search([PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_PRINT], $subcomponents);
        array_splice($subcomponents, $pos, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_EMBED]));
        array_splice($subcomponents, $pos+1, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_API]));

        if ($component == [PoP_Module_Processor_DropdownButtonControls::class, PoP_Module_Processor_DropdownButtonControls::COMPONENT_DROPDOWNBUTTONCONTROL_RESULTSSHARE]) {
            array_splice($subcomponents, $pos, 0, array([GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_COPYSEARCHURL]));
        }
        return $subcomponents;
    }
}

/**
 * Initialization
 */
new PoP_CoreProcessors_Bootstrap_Hooks();
