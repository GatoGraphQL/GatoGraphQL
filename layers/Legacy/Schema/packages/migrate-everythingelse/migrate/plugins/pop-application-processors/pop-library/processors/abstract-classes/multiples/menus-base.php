<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

abstract class PoP_Module_Processor_MenuMultiplesBase extends PoP_Module_Processor_MultiplesBase
{
    protected function getActiveLinkMenuItemIds(array $module, array &$props)
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_Module_Processor_MenuMultiplesBase:active-link-menu-item-ids',
            array(),
            $module,
            $props
        );
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // Add "active" to current links in corresponding links
        $this->addJsmethod($ret, 'activeLinks');
        
        return $ret;
    }

    public function getMutableonrequestJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestJsconfiguration($module, $props);

        if ($active_link_menu_item_ids = $this->getActiveLinkMenuItemIds($module, $props)) {
            $ret['activeLinks']['active-link-menu-item-ids'] = $active_link_menu_item_ids;
        }

        return $ret;
    }
}
