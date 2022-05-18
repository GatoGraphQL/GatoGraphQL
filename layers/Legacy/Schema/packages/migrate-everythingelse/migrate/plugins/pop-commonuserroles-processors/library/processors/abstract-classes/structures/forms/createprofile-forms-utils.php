<?php

abstract class PoP_Module_Processor_CreatProfileFormsUtils
{
    public static function getFormSubmodules(array $componentVariation, &$components, $processor)
    {
        if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
            // Add extra components
            array_splice(
                $components, 
                array_search(
                    [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_DESCRIPTION], 
                    $components
                )+1, 
                0, 
                [
                    [GD_URE_Module_Processor_FormGroups::class, GD_URE_Module_Processor_FormGroups::MODULE_URE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES],
                    [PoP_Module_Processor_Dividers::class, PoP_Module_Processor_Dividers::MODULE_COLLAPSIBLEDIVIDER],
                ]
            );
        }
    }
}
