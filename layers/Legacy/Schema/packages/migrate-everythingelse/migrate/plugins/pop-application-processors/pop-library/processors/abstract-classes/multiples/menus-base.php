<?php

abstract class PoP_Module_Processor_MenuMultiplesBase extends PoP_Module_Processor_MultiplesBase
{
    protected function getActiveLinkMenuItemIds(array $componentVariation, array &$props)
    {
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_MenuMultiplesBase:active-link-menu-item-ids',
            array(),
            $componentVariation,
            $props
        );
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // Add "active" to current links in corresponding links
        $this->addJsmethod($ret, 'activeLinks');
        
        return $ret;
    }

    public function getMutableonrequestJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestJsconfiguration($componentVariation, $props);

        if ($active_link_menu_item_ids = $this->getActiveLinkMenuItemIds($componentVariation, $props)) {
            $ret['activeLinks']['active-link-menu-item-ids'] = $active_link_menu_item_ids;
        }

        return $ret;
    }
}
