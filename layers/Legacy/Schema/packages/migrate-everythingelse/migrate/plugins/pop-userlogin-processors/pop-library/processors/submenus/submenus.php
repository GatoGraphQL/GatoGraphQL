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

    public function getClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                // Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
                return 'btn btn-link';
        }

        return parent::getClass($componentVariation);
    }
    public function getXsClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                // Using btn-link instead of btn-success because the 'active' cannot be calculated on runtime, and btn-link does not paint the active in any different way
                return 'btn btn-default btn-block';
        }

        return parent::getClass($componentVariation);
    }
    public function getDropdownClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                return 'btn-link';
        }

        return parent::getDropdownClass($componentVariation);
    }

    public function getRoutes(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                $ret = array(
                    POP_USERLOGIN_ROUTE_LOGIN => array(),
                );

                // Allow for the Create Profiles links to be added by User Role Editor
                return \PoP\Root\App::applyFilters('PoP_Module_Processor_SubMenus:routes', $ret);
        }

        return parent::getRoutes($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBMENU_ACCOUNT:
                $this->appendProp($componentVariation, $props, 'class', 'visible-notloggedin');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


