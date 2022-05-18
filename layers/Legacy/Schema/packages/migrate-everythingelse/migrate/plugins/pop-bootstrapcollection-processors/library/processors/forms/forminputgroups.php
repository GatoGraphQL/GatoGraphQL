<?php

class GD_Core_Bootstrap_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FILTERINPUTGROUP_MODERATEDPOSTSTATUS = 'filterinputgroup-moderatedpoststatus';
    public final const COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS = 'filterinputgroup-unmoderatedpoststatus';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUTGROUP_MODERATEDPOSTSTATUS],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_MODERATEDPOSTSTATUS:
            case self::COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_MODERATEDPOSTSTATUS:
            case self::COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FILTERINPUTGROUP_MODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS],
            self::COMPONENT_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



