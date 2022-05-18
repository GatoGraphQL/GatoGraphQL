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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_LOGIN],
            [self::class, self::COMPONENT_BLOCK_LOSTPWD],
            [self::class, self::COMPONENT_BLOCK_LOSTPWDRESET],
            [self::class, self::COMPONENT_BLOCK_LOGOUT],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_LOGOUT => POP_USERLOGIN_ROUTE_LOGOUT,
            self::COMPONENT_BLOCK_LOSTPWD => POP_USERLOGIN_ROUTE_LOSTPWD,
            self::COMPONENT_BLOCK_LOSTPWDRESET => POP_USERLOGIN_ROUTE_LOSTPWDRESET,
            self::COMPONENT_BLOCK_LOGIN => POP_USERLOGIN_ROUTE_LOGIN,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getSubmenuSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOGIN:
            case self::COMPONENT_BLOCK_LOSTPWD:
            case self::COMPONENT_BLOCK_LOSTPWDRESET:
                return [PoP_Module_Processor_SubMenus::class, PoP_Module_Processor_SubMenus::COMPONENT_SUBMENU_ACCOUNT];
        }

        return parent::getSubmenuSubmodule($component);
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOGIN:
            case self::COMPONENT_BLOCK_LOSTPWD:
            case self::COMPONENT_BLOCK_LOSTPWDRESET:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_ACCOUNT];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);

        $inner_components = array(
            self::COMPONENT_BLOCK_LOGIN => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::COMPONENT_DATALOAD_LOGIN],
            self::COMPONENT_BLOCK_LOSTPWD => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::COMPONENT_DATALOAD_LOSTPWD],
            self::COMPONENT_BLOCK_LOSTPWDRESET => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::COMPONENT_DATALOAD_LOSTPWDRESET],
            self::COMPONENT_BLOCK_LOGOUT => [PoP_UserLogin_Module_Processor_Dataloads::class, PoP_UserLogin_Module_Processor_Dataloads::COMPONENT_DATALOAD_LOGOUT],
        );

        if ($inner = $inner_components[$component[1]] ?? null) {
            $ret[] = $inner;
        }

        if ($component == [self::class, self::COMPONENT_BLOCK_LOGIN]) {
            $ret[] = [PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME];
        }

        return $ret;
    }

    protected function getDescription(array $component, array &$props)
    {
        $cmsService = CMSServiceFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOGOUT:
                // Notice that it works for the domain from wherever this block is being fetched from!
                return sprintf(
                    '<p class="visible-notloggedin-%s"><em>%s</em></p>',
                    RequestUtils::getDomainId($cmsService->getSiteURL()),
                    TranslationAPIFacade::getInstance()->__('You are not logged in.', 'pop-coreprocessors')
                );
        }

        return parent::getDescription($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOGIN:
                $this->appendProp([[PoP_Module_Processor_UserLoggedIns::class, PoP_Module_Processor_UserLoggedIns::COMPONENT_USERACCOUNT_USERLOGGEDINWELCOME]], $props, 'class', 'well');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



