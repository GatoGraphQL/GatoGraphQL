<?php

class PoP_UserAvatarProcessors_UserPlatformProcessors_CreateUpdateUser_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'pop_module:createuser:components', 
            $this->getComponentSubmodules(...), 
            10, 
            3
        );
    }

    public function getComponentSubmodules($components, array $module, $processor)
    {

        // Add After the email
        $extra_components = array(
            [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_COLLAPSIBLEDIVIDER],
            [PoP_Module_Processor_FileUploadPictures::class, PoP_Module_Processor_FileUploadPictures::MODULE_FILEUPLOAD_PICTURE],
        );
        array_splice($components, array_search([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_EMAIL], $components)+1, 0, $extra_components);
        
        return $components;
    }
}

/**
 * Initialize
 */
new PoP_UserAvatarProcessors_UserPlatformProcessors_CreateUpdateUser_Hooks();
