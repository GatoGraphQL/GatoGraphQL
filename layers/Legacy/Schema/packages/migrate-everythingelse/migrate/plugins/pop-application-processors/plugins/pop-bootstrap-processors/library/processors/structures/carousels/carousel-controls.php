<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

class PoP_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_USERS = 'carouselcontrols-users';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_USERS],
        );
    }

    public function getControlClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($componentVariation);
    }

    public function getTitleClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($componentVariation);
    }
    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return getRouteIcon(UsersModuleConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Users', 'poptheme-wassup');
        }

        return parent::getTitle($componentVariation, $props);
    }
    protected function getTitleLink(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_USERS:
                return RouteUtils::getRouteURL(UsersModuleConfiguration::getUsersRoute());
        }

        return parent::getTitleLink($componentVariation, $props);
    }
}


