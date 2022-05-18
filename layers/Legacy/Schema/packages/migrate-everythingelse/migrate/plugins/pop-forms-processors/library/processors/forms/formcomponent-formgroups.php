<?php

class PoP_Module_Processor_FormComponentGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES = 'filtercomponentgroup-selectabletypeahead-profiles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::MODULE_FILTERCOMPONENTGROUP_SELECTABLETYPEAHEAD_PROFILES => [PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::class, PoP_Module_Processor_UserSelectableTypeaheadFilterInputs::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



