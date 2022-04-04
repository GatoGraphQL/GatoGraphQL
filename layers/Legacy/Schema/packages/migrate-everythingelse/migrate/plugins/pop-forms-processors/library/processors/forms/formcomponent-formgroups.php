<?php

class PoP_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES = 'filtercomponentgroup-selectabletypeahead-profiles';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
        );
    }

    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES => [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



