<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_GenericFormsProcessors_Bootstrap_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_DropdownButtonControls:modules:share',
            array($this, 'getShareSubmodules'),
            10,
            2
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            array($this, 'getDropdownSubmodules'),
            0,
            2
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            array($this, 'getSocialmediaSubmodules'),
            10,
            2
        );
    }

    public function getDropdownSubmodules($submodules, array $module)
    {
        switch ($module[1]) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                array_splice(
                    $submodules, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $submodules
                    ), 
                    0, 
                    array(
                        [PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::class, PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                array_splice(
                    $submodules, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $submodules
                    ), 
                    0, 
                    array(
                        [PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::class, PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::MODULE_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                array_splice(
                    $submodules, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_PREVIEWDROPDOWN], $submodules
                    ), 
                    0, 
                    array(
                        [PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;
        }
        
        return $submodules;
    }

    public function getSocialmediaSubmodules($submodules, array $module)
    {
        switch ($module[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA:
                $submodules[] = [PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::class, PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA];
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_USERSOCIALMEDIA:
                $submodules[] = [PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::class, PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA];
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_TAGSOCIALMEDIA:
                $submodules[] = [PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA];
                break;
        }
        
        return $submodules;
    }

    public function getShareSubmodules($submodules, array $module)
    {

        // Insert before the Embed button
        array_splice(
            $submodules, 
            array_search(
                [GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_EMBED], 
                $submodules
            ), 
            0, 
            array(
                [PoPCore_GenericForms_Module_Processor_AnchorControls::class, PoPCore_GenericForms_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SHAREBYEMAIL]
            )
        );
        return $submodules;
    }
}

/**
 * Initialization
 */
new PoP_GenericFormsProcessors_Bootstrap_Hooks();
