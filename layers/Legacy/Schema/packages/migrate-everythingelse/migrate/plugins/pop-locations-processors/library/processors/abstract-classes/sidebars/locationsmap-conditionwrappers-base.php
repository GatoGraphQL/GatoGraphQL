<?php

abstract class GD_EM_Module_Processor_LocationMapConditionWrappersBase extends PoP_Module_Processor_ConditionWrapperBase
{
    public function getLocationlinksTemplate(array $componentVariation)
    {
        return null;
    }

    public function getMapSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MapIndividuals::class, PoP_Module_Processor_MapIndividuals::MODULE_MAP_SIDEBARINDIVIDUAL];
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        if ($locationslinks = $this->getLocationlinksTemplate($componentVariation)) {
            $ret[] = $locationslinks;
        }
        if ($map = $this->getMapSubmodule($componentVariation)) {
            $ret[] = $map;
        }

        return $ret;
    }

    public function getConditionField(array $componentVariation): ?string
    {
        return 'hasLocation';
    }

    public function getConditionFailedSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionFailedSubmodules($componentVariation);

        $ret[] = [GD_EM_Module_Processor_WidgetMessages::class, GD_EM_Module_Processor_WidgetMessages::MODULE_EM_MESSAGE_NOLOCATION];

        return $ret;
    }
}
