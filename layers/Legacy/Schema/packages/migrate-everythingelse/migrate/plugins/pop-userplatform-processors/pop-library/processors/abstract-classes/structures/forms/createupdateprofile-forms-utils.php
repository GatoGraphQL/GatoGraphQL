<?php

abstract class PoP_Module_Processor_CreateUpdateProfileFormsUtils
{
    public static function getFormSubmodules(array $component, &$components, $processor)
    {
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_DESCRIPTION], 
                $components
            ), 
            0, 
            array(
                [PoP_Module_Processor_ProfileFormGroups::class, PoP_Module_Processor_ProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_SHORTDESCRIPTION],
            )
        );
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_EMAIL], 
                $components
            )+1, 
            0, 
            array(
                [PoP_Module_Processor_NoLabelProfileFormGroups::class, PoP_Module_Processor_NoLabelProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_DISPLAYEMAIL],
            )
        );
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_USERWEBSITEURL], 
                $components
            )+1, 
            0, 
            // Allow AgendaUrbana to remove LinkedIn            
            \PoP\Root\App::applyFilters(
                'PoP_Module_Processor_CreateUpdateProfileFormsUtils:socialmedia',
                array(
                    [PoP_Module_Processor_ProfileFormGroups::class, PoP_Module_Processor_ProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_FACEBOOK],
                    [PoP_Module_Processor_ProfileFormGroups::class, PoP_Module_Processor_ProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_TWITTER],
                    [PoP_Module_Processor_ProfileFormGroups::class, PoP_Module_Processor_ProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_LINKEDIN],
                    [PoP_Module_Processor_ProfileFormGroups::class, PoP_Module_Processor_ProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_YOUTUBE],
                    [PoP_Module_Processor_ProfileFormGroups::class, PoP_Module_Processor_ProfileFormGroups::COMPONENT_FORMINPUTGROUP_CUP_INSTAGRAM],
                )
            )
        );
    }
}
