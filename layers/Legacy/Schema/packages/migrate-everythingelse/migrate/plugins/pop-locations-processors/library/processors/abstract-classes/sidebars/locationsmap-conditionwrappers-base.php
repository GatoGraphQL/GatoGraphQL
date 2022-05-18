<?php

abstract class GD_EM_Module_Processor_LocationMapConditionWrappersBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getLocationlinksTemplate(array $component)
    {
        return null;
    }

    public function getMapSubmodule(array $component)
    {
        return [PoP_Module_Processor_MapIndividuals::class, PoP_Module_Processor_MapIndividuals::MODULE_MAP_SIDEBARINDIVIDUAL];
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        if ($locationslinks = $this->getLocationlinksTemplate($component)) {
            $ret[] = $locationslinks;
        }
        if ($map = $this->getMapSubmodule($component)) {
            $ret[] = $map;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        return 'hasLocation';
    }

    public function getConditionFailedSubmodules(array $component)
    {
        $ret = parent::getConditionFailedSubmodules($component);

        $ret[] = [GD_EM_Module_Processor_WidgetMessages::class, GD_EM_Module_Processor_WidgetMessages::MODULE_EM_MESSAGE_NOLOCATION];

        return $ret;
    }
}
