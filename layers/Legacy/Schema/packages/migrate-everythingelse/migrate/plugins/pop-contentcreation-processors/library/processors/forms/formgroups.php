<?php

class PoP_ContentCreation_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_WHYFLAG = 'gf-forminputgroup-field-whyflag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_WHYFLAG],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_WHYFLAG => [PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, PoP_ContentCreation_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYFLAG],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



