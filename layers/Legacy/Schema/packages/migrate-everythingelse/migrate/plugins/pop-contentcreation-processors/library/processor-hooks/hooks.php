<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_GenericFormsProcessors_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_DropdownButtonQuicklinks:modules',
            array($this, 'getDropdownSubmodules'),
            10,
            2
        );
        \PoP\Root\App::getHookManager()->addFilter(
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
                $submodules[] = [PoP_ContentCreation_Module_Processor_PostViewComponentButtons::class, PoP_ContentCreation_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_PREVIEWDROPDOWN];
                break;
        }
        
        return $submodules;
    }

    public function getSocialmediaSubmodules($submodules, array $module)
    {
        switch ($module[1]) {
            case PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTSECINTERACTIONS:
                $submodules[] = [PoP_ContentCreation_Module_Processor_PostViewComponentButtons::class, PoP_ContentCreation_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_FLAG_SOCIALMEDIA];
                break;
        }
        
        return $submodules;
    }
}

/**
 * Initialization
 */
new PoP_GenericFormsProcessors_Hooks();
