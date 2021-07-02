<?php

class PoP_Module_Processor_UserAccountGroups extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_GROUP_LOGGEDINUSERDATA = 'group-loggedinuserdata';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GROUP_LOGGEDINUSERDATA],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        $routes = array(
            self::MODULE_GROUP_LOGGEDINUSERDATA => POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA,
        );
        return $routes[$module[1]] ?? parent::getRelevantRoute($module, $props);
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_GROUP_LOGGEDINUSERDATA:
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
            case self::MODULE_GROUP_LOGGEDINUSERDATA:
                $this->appendProp($module, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


