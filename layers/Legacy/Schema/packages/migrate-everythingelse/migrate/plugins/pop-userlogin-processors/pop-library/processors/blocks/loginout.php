<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserLogin_Module_Processor_Blocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_LOGIN = 'block-login';
    public final const MODULE_BLOCK_LOSTPWD = 'block-lostpwd';
    public final const MODULE_BLOCK_LOSTPWDRESET = 'block-lostpwdreset';
    public final const MODULE_BLOCK_LOGOUT = 'block-logout';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOGIN],
            [self::class, self::MODULE_BLOCK_LOSTPWD],
            [self::class, self::MODULE_BLOCK_LOSTPWDRESET],
            [self::class, self::MODULE_BLOCK_LOGOUT],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_LOGOUT => POP_USERLOGIN_ROUTE_LOGOUT,
            self::MODULE_BLOCK_LOSTPWD => POP_USERLOGIN_ROUTE_LOSTPWD,
            self::MODULE_BLOCK_LOSTPWDRESET => POP_USERLOGIN_ROUTE_LOSTPWDRESET,
            self::MODULE_BLOCK_LOGIN => POP_USERLOGIN_ROUTE_LOGIN,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getSubmenuSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOGIN:
            case self::MODULE_BLOCK_LOSTPWD:
            case self::MODULE_BLOCK_LOSTPWDRESET:
                return [PoP_Module_Processor_SubMenus::class, PoP_Module_Processor_SubMenus::MODULE_SUBMENU_ACCOUNT];
        }

        return parent::getSubmenuSubmodule($module);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOGIN:
            case self::MODULE_BLOCK_LOSTPWD:
            case self::MODULE_BLOCK_LOSTPWDRESET:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_ACCOUNT];
        }

        return parent::getControlgroupTopSubmodule($module);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $inner_modules = array(
            self::MODULE_BLOCK_LOGIN => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::MODULE_DATALOAD_LOGIN],
            self::MODULE_BLOCK_LOSTPWD => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::MODULE_DATALOAD_LOSTPWD],
            self::MODULE_BLOCK_LOSTPWDRESET => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::MODULE_DATALOAD_LOSTPWDRESET],
            self::MODULE_BLOCK_LOGOUT => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::MODULE_DATALOAD_LOGOUT],
        );

        if ($inner = $inner_modules[$module[1]] ?? null) {
            $ret[] = $inner;
        }

        if ($module == [self::class, self::MODULE_BLOCK_LOGIN]) {
            $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::MODULE_USERACCOUNT_USERLOGGEDINWELCOME];
        }

        return $ret;
    }

    protected function getDescription(array $module, array &$props)
    {
        $cmsService = CMSServiceFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOGOUT:
                // Notice that it works for the domain from wherever this block is being fetched from!
                return sprintf(
                    '<p class="visible-notloggedin-%s"><em>%s</em></p>',
                    RequestUtils::getDomainId($cmsService->getSiteURL()),
                    TranslationAPIFacade::getInstance()->__('You are not logged in.', 'pop-coreprocessors')
                );
        }

        return parent::getDescription($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOGIN:
                $this->appendProp([[PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::MODULE_USERACCOUNT_USERLOGGEDINWELCOME]], $props, 'class', 'well');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



