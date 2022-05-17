<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

class PoP_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_USERS = 'carouselcontrols-users';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_USERS],
        );
    }

    public function getControlClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($module);
    }

    public function getTitleClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($module);
    }
    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return getRouteIcon(UsersModuleConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Users', 'poptheme-wassup');
        }

        return parent::getTitle($module, $props);
    }
    protected function getTitleLink(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return RouteUtils::getRouteURL(UsersModuleConfiguration::getUsersRoute());
        }

        return parent::getTitleLink($module, $props);
    }
}


