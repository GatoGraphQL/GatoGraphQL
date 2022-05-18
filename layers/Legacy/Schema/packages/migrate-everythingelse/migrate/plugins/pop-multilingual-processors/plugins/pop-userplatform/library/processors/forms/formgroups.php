<?php

class GD_QT_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_QT_FORMINPUTGROUP_LANGUAGE = 'qt-forminputgroup-language';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_QT_FORMINPUTGROUP_LANGUAGE],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_QT_FORMINPUTGROUP_LANGUAGE => [GD_QT_Module_Processor_SelectFormInputs::class, GD_QT_Module_Processor_SelectFormInputs::COMPONENT_QT_FORMINPUT_LANGUAGE],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



