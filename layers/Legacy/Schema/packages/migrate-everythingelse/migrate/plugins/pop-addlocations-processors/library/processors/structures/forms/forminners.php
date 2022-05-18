<?php

class GD_EM_Module_Processor_CreateLocationFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_CREATELOCATION = 'em-forminner-createlocation';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_CREATELOCATION],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);
    
        switch ($component[1]) {
            case self::MODULE_FORMINNER_CREATELOCATION:
                $ret = array_merge(
                    $ret,
                    array(
                        [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONLAT],
                        [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONLNG],
                        [GD_EM_Module_Processor_CreateLocationFormGroups::class, GD_EM_Module_Processor_CreateLocationFormGroups::MODULE_FORMINPUTGROUP_EM_LOCATIONNAME],
                        [GD_EM_Module_Processor_CreateLocationFormGroups::class, GD_EM_Module_Processor_CreateLocationFormGroups::MODULE_FORMINPUTGROUP_EM_LOCATIONADDRESS],
                        [GD_EM_Module_Processor_CreateLocationFormGroups::class, GD_EM_Module_Processor_CreateLocationFormGroups::MODULE_FORMINPUTGROUP_EM_LOCATIONTOWN],
                        [GD_EM_Module_Processor_CreateLocationFormGroups::class, GD_EM_Module_Processor_CreateLocationFormGroups::MODULE_FORMINPUTGROUP_EM_LOCATIONSTATE],
                        [GD_EM_Module_Processor_CreateLocationFormGroups::class, GD_EM_Module_Processor_CreateLocationFormGroups::MODULE_FORMINPUTGROUP_EM_LOCATIONPOSTCODE],
                        [GD_EM_Module_Processor_CreateLocationFormGroups::class, GD_EM_Module_Processor_CreateLocationFormGroups::MODULE_FORMINPUTGROUP_EM_LOCATIONREGION],
                        [GD_EM_Module_Processor_CreateLocationFormGroups::class, GD_EM_Module_Processor_CreateLocationFormGroups::MODULE_FORMINPUTGROUP_EM_LOCATIONCOUNTRY],
                        [GD_EM_Module_Processor_SubmitButtons::class, GD_EM_Module_Processor_SubmitButtons::MODULE_EM_SUBMITBUTTON_ADDLOCATION],
                    )
                );
                break;
        }

        return $ret;
    }
}



