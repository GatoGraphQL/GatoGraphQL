<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

class PoP_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const COMPONENT_CAROUSELCONTROLS_USERS = 'carouselcontrols-users';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSELCONTROLS_USERS],
        );
    }

    public function getControlClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_USERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($component);
    }

    public function getTitleClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_USERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($component);
    }
    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_USERS:
                return getRouteIcon(UsersModuleConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Users', 'poptheme-wassup');
        }

        return parent::getTitle($component, $props);
    }
    protected function getTitleLink(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_USERS:
                return RouteUtils::getRouteURL(UsersModuleConfiguration::getUsersRoute());
        }

        return parent::getTitleLink($component, $props);
    }
}


