<?php

class PoP_Volunteering_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_PHONE = 'gf-forminputgroup-field-phone';
    public final const MODULE_FORMINPUTGROUP_WHYVOLUNTEER = 'gf-forminputgroup-field-whyvolunteer';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_PHONE],
            [self::class, self::MODULE_FORMINPUTGROUP_WHYVOLUNTEER],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_PHONE => [PoP_Volunteering_Module_Processor_TextFormInputs::class, PoP_Volunteering_Module_Processor_TextFormInputs::MODULE_FORMINPUT_PHONE],
            self::MODULE_FORMINPUTGROUP_WHYVOLUNTEER => [PoP_Volunteering_Module_Processor_TextareaFormInputs::class, PoP_Volunteering_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYVOLUNTEER],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }
}



