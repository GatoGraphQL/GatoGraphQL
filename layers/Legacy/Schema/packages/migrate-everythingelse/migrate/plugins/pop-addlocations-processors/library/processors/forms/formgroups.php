<?php

class GD_EM_Module_Processor_CreateLocationFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_EM_LOCATIONNAME = 'forminputgroup-location_name';
    public final const COMPONENT_FORMINPUTGROUP_EM_LOCATIONADDRESS = 'forminputgroup-location_address';
    public final const COMPONENT_FORMINPUTGROUP_EM_LOCATIONTOWN = 'forminputgroup-location_town';
    public final const COMPONENT_FORMINPUTGROUP_EM_LOCATIONSTATE = 'forminputgroup-location_state';
    public final const COMPONENT_FORMINPUTGROUP_EM_LOCATIONPOSTCODE = 'forminputgroup-location_postcode';
    public final const COMPONENT_FORMINPUTGROUP_EM_LOCATIONREGION = 'forminputgroup-location_region';
    public final const COMPONENT_FORMINPUTGROUP_EM_LOCATIONCOUNTRY = 'forminputgroup-location_country';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONNAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONADDRESS],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONTOWN],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONSTATE],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONPOSTCODE],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONREGION],
            [self::class, self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONCOUNTRY],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONNAME => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::COMPONENT_FORMINPUT_EM_LOCATIONNAME],
            self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONADDRESS => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::COMPONENT_FORMINPUT_EM_LOCATIONADDRESS],
            self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONTOWN => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::COMPONENT_FORMINPUT_EM_LOCATIONTOWN],
            self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONSTATE => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::COMPONENT_FORMINPUT_EM_LOCATIONSTATE],
            self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONPOSTCODE => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::COMPONENT_FORMINPUT_EM_LOCATIONPOSTCODE],
            self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONREGION => [GD_EM_Module_Processor_CreateLocationTextFormInputs::class, GD_EM_Module_Processor_CreateLocationTextFormInputs::COMPONENT_FORMINPUT_EM_LOCATIONREGION],
            self::COMPONENT_FORMINPUTGROUP_EM_LOCATIONCOUNTRY => [GD_EM_Module_Processor_CreateLocationSelectFormInputs::class, GD_EM_Module_Processor_CreateLocationSelectFormInputs::COMPONENT_FORMINPUT_EM_LOCATIONCOUNTRY],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



