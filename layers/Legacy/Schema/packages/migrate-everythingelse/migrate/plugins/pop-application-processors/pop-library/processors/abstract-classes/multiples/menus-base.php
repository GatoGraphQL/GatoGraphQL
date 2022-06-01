<?php

abstract class PoP_Module_Processor_MenuMultiplesBase extends PoP_Module_Processor_MultiplesBase
{
    protected function getActiveLinkMenuItemIds(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_MenuMultiplesBase:active-link-menu-item-ids',
            array(),
            $component,
            $props
        );
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Add "active" to current links in corresponding links
        $this->addJsmethod($ret, 'activeLinks');
        
        return $ret;
    }

    public function getMutableonrequestJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestJsconfiguration($component, $props);

        if ($active_link_menu_item_ids = $this->getActiveLinkMenuItemIds($component, $props)) {
            $ret['activeLinks']['active-link-menu-item-ids'] = $active_link_menu_item_ids;
        }

        return $ret;
    }
}
