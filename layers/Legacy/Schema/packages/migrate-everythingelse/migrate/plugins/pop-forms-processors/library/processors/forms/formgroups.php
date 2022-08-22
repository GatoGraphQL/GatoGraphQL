<?php

class PoP_Module_Processor_FormGroups extends PoP_Module_Processor_FormGroupsBase
{
    public final const COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH = 'submitbuttonformgroup-search';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH,
        );
    }

    public function getFormcontrolClass(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component->name) {
            case self::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH:
                $ret .= ' col-sm-offset-2 col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $components = array(
            self::COMPONENT_SUBMITBUTTONFORMGROUP_SEARCH => [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SEARCH],
        );

        if ($component = $components[$component->name] ?? null) {
            return $component;
        }

        return parent::getComponentSubcomponent($component);
    }
}



