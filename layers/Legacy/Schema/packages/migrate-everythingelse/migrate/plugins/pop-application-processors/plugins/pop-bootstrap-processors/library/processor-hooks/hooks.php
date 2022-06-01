<?php

class PoP_GenericFormsProcessors_Bootstrap_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_DropdownButtonControls:components:share',
            $this->getShareSubcomponents(...),
            10,
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
            10,
            2
        );
    }

    public function getDropdownSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_POSTSHARE:
                array_splice(
                    $subcomponents, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subcomponents
                    ), 
                    0, 
                    array(
                        [PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::class, PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_USERSHARE:
                array_splice(
                    $subcomponents, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subcomponents
                    ), 
                    0, 
                    array(
                        [PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::class, PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;

            case PoP_Module_Processor_DropdownButtonQuicklinks::COMPONENT_DROPDOWNBUTTONQUICKLINK_TAGSHARE:
                array_splice(
                    $subcomponents, 
                    array_search(
                        [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_PREVIEWDROPDOWN], $subcomponents
                    ), 
                    0, 
                    array(
                        [PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_PREVIEWDROPDOWN]
                    )
                );
                break;
        }
        
        return $subcomponents;
    }

    public function getSocialmediaSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA:
                $subcomponents[] = [PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::class, PoPGenericForms_Bootstrap_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_SHAREBYEMAIL_SOCIALMEDIA];
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_USERSOCIALMEDIA:
                $subcomponents[] = [PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::class, PoP_GenericForms_Bootstrap_Module_Processor_UserViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USER_SHAREBYEMAIL_SOCIALMEDIA];
                break;

            case PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_TAGSOCIALMEDIA:
                $subcomponents[] = [PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_TagViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_TAG_SHAREBYEMAIL_SOCIALMEDIA];
                break;
        }
        
        return $subcomponents;
    }

    public function getShareSubcomponents($subcomponents, \PoP\ComponentModel\Component\Component $component)
    {

        // Insert before the Embed button
        array_splice(
            $subcomponents, 
            array_search(
                [GD_Core_Bootstrap_Module_Processor_AnchorControls::class, GD_Core_Bootstrap_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_EMBED], 
                $subcomponents
            ), 
            0, 
            array(
                [PoPCore_GenericForms_Module_Processor_AnchorControls::class, PoPCore_GenericForms_Module_Processor_AnchorControls::COMPONENT_ANCHORCONTROL_SHAREBYEMAIL]
            )
        );
        return $subcomponents;
    }
}

/**
 * Initialization
 */
new PoP_GenericFormsProcessors_Bootstrap_Hooks();
