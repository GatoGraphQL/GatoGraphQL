<?php

class GetPoPDemo_Module_Processor_CustomGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_GETPOPDEMO_GROUP_HOMETOP = 'group-getpopdemo-hometop';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GETPOPDEMO_GROUP_HOMETOP],
        );
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
    
        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_GROUP_HOMETOP:
                // Needed to recalculate the waypoints for the sideinfo waypoints effect that shows the filter when reaching the top of the All Content block
                $this->addJsmethod($ret, 'onBootstrapEventWindowResize');

                // It will remove class "hidden" through .js if there is no cookie
                $this->addJsmethod($ret, 'cookieToggleClass');
                break;
        }

        return $ret;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_GROUP_HOMETOP:
                $ret[] = [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::MODULE_GROUP_HOME_COMPACTWELCOME];
                if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
                    $ret[] = [GetPoPDemo_Module_Processor_TopLevelCollapseComponents::class, GetPoPDemo_Module_Processor_TopLevelCollapseComponents::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP];
                }
                break;
        }

        return $ret;
    }
}


