<?php

abstract class PoP_Module_Processor_CreateUpdateProfileOrganizationFormsUtils
{
    public static function getFormSubmodules(array $componentVariation, &$components, $processor)
    {
        // Add extra components
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_ProfileFormGroups::class, PoP_Module_Processor_ProfileFormGroups::MODULE_FORMINPUTGROUP_CUP_SHORTDESCRIPTION], 
                $components
            ), 
            0, 
            [
                [GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_ORGANIZATIONTYPES],
                [GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_ORGANIZATIONCATEGORIES],
            ]
        );
        array_splice(
            $components, 
            array_search(
                [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_USERWEBSITEURL], 
                $components
            ), 
            0, 
            [
                [GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_CUP_CONTACTNUMBER],
                [GD_CommonUserRoles_Module_Processor_ProfileFormGroups::class, GD_CommonUserRoles_Module_Processor_ProfileFormGroups::MODULE_URE_FORMINPUTGROUP_CUP_CONTACTPERSON],
            ]
        );
    }
}
