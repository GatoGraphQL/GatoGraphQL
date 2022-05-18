<?php

class GD_Core_Bootstrap_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS = 'filterinputgroup-moderatedpoststatus';
    public final const MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS = 'filterinputgroup-unmoderatedpoststatus';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS],
            [self::class, self::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS:
            case self::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS:
            case self::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::MODULE_FILTERINPUTGROUP_MODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS],
            self::MODULE_FILTERINPUTGROUP_UNMODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputs::class, PoP_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



