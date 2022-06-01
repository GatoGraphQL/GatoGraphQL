<?php

class GetPoPDemo_Module_Processor_CustomGroups extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_GETPOPDEMO_GROUP_HOMETOP = 'group-getpopdemo-hometop';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_GETPOPDEMO_GROUP_HOMETOP,
        );
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
    
        switch ($component->name) {
            case self::COMPONENT_GETPOPDEMO_GROUP_HOMETOP:
                // Needed to recalculate the waypoints for the sideinfo waypoints effect that shows the filter when reaching the top of the All Content block
                $this->addJsmethod($ret, 'onBootstrapEventWindowResize');

                // It will remove class "hidden" through .js if there is no cookie
                $this->addJsmethod($ret, 'cookieToggleClass');
                break;
        }

        return $ret;
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_GETPOPDEMO_GROUP_HOMETOP:
                $ret[] = [PoP_Module_Processor_CustomGroups::class, PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_HOME_COMPACTWELCOME];
                if (defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')) {
                    $ret[] = [GetPoPDemo_Module_Processor_TopLevelCollapseComponents::class, GetPoPDemo_Module_Processor_TopLevelCollapseComponents::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP];
                }
                break;
        }

        return $ret;
    }
}


