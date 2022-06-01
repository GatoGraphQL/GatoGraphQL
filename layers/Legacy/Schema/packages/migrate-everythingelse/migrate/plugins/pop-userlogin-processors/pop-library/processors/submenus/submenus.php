<?php

class PoP_Module_Processor_SubMenus extends PoP_Module_Processor_SubMenusBase
{
    public final const COMPONENT_SUBMENU_ACCOUNT = 'submenu-account';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBMENU_ACCOUNT],
        );
    }

    public function getClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_ACCOUNT:
                // Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
                return 'btn btn-link';
        }

        return parent::getClass($component);
    }
    public function getXsClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_ACCOUNT:
                // Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
                return 'btn btn-default btn-block';
        }

        return parent::getClass($component);
    }
    public function getDropdownClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_ACCOUNT:
                return 'btn-link';
        }

        return parent::getDropdownClass($component);
    }

    public function getRoutes(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_ACCOUNT:
                $ret = array(
                    POP_USERLOGIN_ROUTE_LOGIN => array(),
                );

                // Allow for the Create Profiles links to be added by User Role Editor
                return \PoP\Root\App::applyFilters('PoP_Module_Processor_SubMenus:routes', $ret);
        }

        return parent::getRoutes($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_ACCOUNT:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_ACCOUNT:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMENU_ACCOUNT:
                $this->appendProp($component, $props, 'class', 'visible-notloggedin');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


