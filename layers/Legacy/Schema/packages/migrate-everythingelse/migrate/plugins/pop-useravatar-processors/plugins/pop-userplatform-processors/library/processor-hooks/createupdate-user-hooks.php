<?php

class PoP_UserAvatarProcessors_UserPlatformProcessors_CreateUpdateUser_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'pop_component:createuser:components', 
            $this->getComponentSubcomponents(...), 
            10, 
            3
        );
    }

    public function getComponentSubcomponents($components, array $component, $processor)
    {

        // Add After the email
        $extra_components = array(
            [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::COMPONENT_COLLAPSIBLEDIVIDER],
            [PoP_Module_Processor_FileUploadPictures::class, PoP_Module_Processor_FileUploadPictures::COMPONENT_FILEUPLOAD_PICTURE],
        );
        array_splice($components, array_search([PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_EMAIL], $components)+1, 0, $extra_components);
        
        return $components;
    }
}

/**
 * Initialize
 */
new PoP_UserAvatarProcessors_UserPlatformProcessors_CreateUpdateUser_Hooks();
