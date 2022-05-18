<?php

class GD_EM_Module_Processor_CreateLocationFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_EM_LOCATIONNAME = 'forminputgroup-location_name';
    public final const MODULE_FORMINPUTGROUP_EM_LOCATIONADDRESS = 'forminputgroup-location_address';
    public final const MODULE_FORMINPUTGROUP_EM_LOCATIONTOWN = 'forminputgroup-location_town';
    public final const MODULE_FORMINPUTGROUP_EM_LOCATIONSTATE = 'forminputgroup-location_state';
    public final const MODULE_FORMINPUTGROUP_EM_LOCATIONPOSTCODE = 'forminputgroup-location_postcode';
    public final const MODULE_FORMINPUTGROUP_EM_LOCATIONREGION = 'forminputgroup-location_region';
    public final const MODULE_FORMINPUTGROUP_EM_LOCATIONCOUNTRY = 'forminputgroup-location_country';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_EM_LOCATIONNAME],
            [self::class, self::MODULE_FORMINPUTGROUP_EM_LOCATIONADDRESS],
            [self::class, self::MODULE_FORMINPUTGROUP_EM_LOCATIONTOWN],
            [self::class, self::MODULE_FORMINPUTGROUP_EM_LOCATIONSTATE],
            [self::class, self::MODULE_FORMINPUTGROUP_EM_LOCATIONPOSTCODE],
            [self::class, self::MODULE_FORMINPUTGROUP_EM_LOCATIONREGION],
            [self::class, self::MODULE_FORMINPUTGROUP_EM_LOCATIONCOUNTRY],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_EM_LOCATIONNAME => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONNAME],
            self::MODULE_FORMINPUTGROUP_EM_LOCATIONADDRESS => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONADDRESS],
            self::MODULE_FORMINPUTGROUP_EM_LOCATIONTOWN => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONTOWN],
            self::MODULE_FORMINPUTGROUP_EM_LOCATIONSTATE => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONSTATE],
            self::MODULE_FORMINPUTGROUP_EM_LOCATIONPOSTCODE => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONPOSTCODE],
            self::MODULE_FORMINPUTGROUP_EM_LOCATIONREGION => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::MODULE_FORMINPUT_EM_LOCATIONREGION],
            self::MODULE_FORMINPUTGROUP_EM_LOCATIONCOUNTRY => [GD_EM_Module_Processor_CreateLocationSelectFormInputs::class, GD_EM_Module_Processor_CreateLocationSelectFormInputs::MODULE_FORMINPUT_EM_LOCATIONCOUNTRY],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



