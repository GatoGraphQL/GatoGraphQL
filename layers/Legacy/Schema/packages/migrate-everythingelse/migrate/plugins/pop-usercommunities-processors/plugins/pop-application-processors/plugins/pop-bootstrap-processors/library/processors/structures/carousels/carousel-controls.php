<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_UserCommunities_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_AUTHORMEMBERS = 'carouselcontrols-members';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS],
        );
    }

    public function getControlClass(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($component);
    }

    public function getTitleClass(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($component);
    }
    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return getRouteIcon(UsersModuleConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Members', 'poptheme-wassup');
        }

        return parent::getTitle($component, $props);
    }
    protected function getTitleLink(array $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
                );
                return RequestUtils::addRoute($url, $routes[$component[1]] ?? null);
        }

        return parent::getTitleLink($component, $props);
    }
}


