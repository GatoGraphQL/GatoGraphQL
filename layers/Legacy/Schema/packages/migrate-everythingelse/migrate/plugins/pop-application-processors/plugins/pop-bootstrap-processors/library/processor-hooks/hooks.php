<?php

class PoP_GenericFormsProcessors_Bootstrap_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonControls:modules:share',
            $this->getShareSubmodules(...),
            10,
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
            10,
            2
        );
    }

    public function getDropdownSubmodules($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                array_splice(
                    $subComponents, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $subComponents
                    ), 
                    0, 
                    array(
                        [PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::class, PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                array_splice(
                    $subComponents, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $subComponents
                    ), 
                    0, 
                    array(
                        [PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::class, PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                array_splice(
                    $subComponents, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $subComponents
                    ), 
                    0, 
                    array(
                        [PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;
        }
        
        return $subComponents;
    }

    public function getSocialmediaSubmodules($subComponents, array $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA:
                $subComponents[] = [PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::class, PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA];
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_USERSOCIALMEDIA:
                $subComponents[] = [PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::class, PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA];
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_TAGSOCIALMEDIA:
                $subComponents[] = [PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA];
                break;
        }
        
        return $subComponents;
    }

    public function getShareSubmodules($subComponents, array $component)
    {

        // Insert before the Embed button
        array_splice(
            $subComponents, 
            array_search(
                [GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_EMBED], 
                $subComponents
            ), 
            0, 
            array(
                [PoPCore_GenericForms_Module_Processor_AnchorControls::class, PoPCore_GenericForms_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SHAREBYEMAIL]
            )
        );
        return $subComponents;
    }
}

/**
 * Initialization
 */
new PoP_GenericFormsProcessors_Bootstrap_Hooks();
