<?php

class PoP_Module_Processor_FormGroups extends PoP_Module_Processor_FormGroupsBase
{
    public final const MODULE_SUBMITBUTTONFORMGROUP_SEARCH = 'submitbuttonformgroup-search';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH],
        );
    }

    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH:
                $ret .= ' col-sm-offset-2 col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH => [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SEARCH],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



