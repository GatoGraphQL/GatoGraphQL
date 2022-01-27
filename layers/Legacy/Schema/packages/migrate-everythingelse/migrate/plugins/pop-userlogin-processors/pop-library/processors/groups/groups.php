<?php

class PoP_Module_Processor_LoginGroups extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_GROUP_LOGIN = 'group-login';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_LOGIN],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_GROUP_LOGIN:
                $ret[] = [PoP_UserLogin_Module_Processor_Blocks::class, PoP_UserLogin_Module_Processor_Blocks::MODULE_BLOCK_LOGIN];
                $ret = array_merge(
                    $ret,
                    PoP_Module_Processor_UserAccountUtils::getLoginModules()
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_GROUP_LOGIN:
                $this->appendProp($module, $props, 'class', 'blockgroup-login');

                // Make the Login Block and others show the submenu
                foreach ($this->getSubmodules($module) as $submodule) {
                    $this->setProp([$submodule], $props, 'show-submenu', true);

                    // Allow to set $props for the extra blocks. Eg: WSL setting the loginBlock for setting the disabled layer
                    $hooks = \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_LoginGroups:props:hooks',
                        array()
                    );
                    foreach ($hooks as $hook) {
                        $hook->setModelProps($module, $props, $this);
                    }
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


