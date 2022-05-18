<?php

class GD_QT_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_QT_FORMINPUTGROUP_LANGUAGE = 'qt-forminputgroup-language';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QT_FORMINPUTGROUP_LANGUAGE],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_QT_FORMINPUTGROUP_LANGUAGE => [GD_QT_Module_Processor_SelectFormInputs::class, GD_QT_Module_Processor_SelectFormInputs::MODULE_QT_FORMINPUT_LANGUAGE],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



