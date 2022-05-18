<?php

class PoP_Module_Processor_SubMenus extends PoP_Module_Processor_SubMenusBase
{
    public final const MODULE_SUBMENU_ACCOUNT = 'submenu-account';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBMENU_ACCOUNT],
        );
    }

    public function getClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                // Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
                return 'btn btn-link';
        }

        return parent::getClass($module);
    }
    public function getXsClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                // Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
                return 'btn btn-default btn-block';
        }

        return parent::getClass($module);
    }
    public function getDropdownClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                return 'btn-link';
        }

        return parent::getDropdownClass($module);
    }

    public function getRoutes(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                $ret = array(
                    POP_USERLOGIN_ROUTE_LOGIN => array(),
                );

                // Allow for the Create Profiles links to be added by User Role Editor
                return \PoP\Root\App::applyFilters('PoP_Module_Processor_SubMenus:routes', $ret);
        }

        return parent::getRoutes($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                $this->appendProp($module, $props, 'class', 'visible-notloggedin');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


