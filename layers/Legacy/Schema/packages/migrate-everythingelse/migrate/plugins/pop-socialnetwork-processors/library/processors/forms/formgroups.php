<?php

class PoP_SocialNetwork_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_MESSAGESUBJECT = 'gf-forminputgroup-field-messagesubject';
    public final const MODULE_FORMINPUTGROUP_MESSAGETOUSER = 'gf-forminputgroup-field-messagetouser';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_MESSAGESUBJECT],
            [self::class, self::MODULE_FORMINPUTGROUP_MESSAGETOUSER],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_MESSAGESUBJECT => [PoP_SocialNetwork_Module_Processor_TextFormInputs::class, PoP_SocialNetwork_Module_Processor_TextFormInputs::MODULE_FORMINPUT_MESSAGESUBJECT],
            self::MODULE_FORMINPUTGROUP_MESSAGETOUSER => [PoP_SocialNetwork_Module_Processor_TextareaFormInputs::class, PoP_SocialNetwork_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGETOUSER],
        );

        if ($component = $components[$componentVariation[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($componentVariation);
    }
}



