<?php

abstract class GD_EM_Module_Processor_LocationMapConditionWrappersBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getLocationlinksTemplate(array $module)
    {
        return null;
    }

    public function getMapSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapIndividuals::class, PoP_Module_Processor_MapIndividuals::MODULE_MAP_SIDEBARINDIVIDUAL];
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        if ($locationslinks = $this->getLocationlinksTemplate($module)) {
            $ret[] = $locationslinks;
        }
        if ($map = $this->getMapSubmodule($module)) {
            $ret[] = $map;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        return 'hasLocation';
    }

    public function getConditionFailedSubmodules(array $module)
    {
        $ret = parent::getConditionFailedSubmodules($module);

        $ret[] = [GD_EM_Module_Processor_WidgetMessages::class, GD_EM_Module_Processor_WidgetMessages::MODULE_EM_MESSAGE_NOLOCATION];

        return $ret;
    }
}
