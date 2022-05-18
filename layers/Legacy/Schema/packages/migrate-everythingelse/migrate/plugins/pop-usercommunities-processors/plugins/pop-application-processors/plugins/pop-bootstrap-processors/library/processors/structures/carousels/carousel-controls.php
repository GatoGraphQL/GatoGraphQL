<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_UserCommunities_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_AUTHORMEMBERS = 'carouselcontrols-members';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS],
        );
    }

    public function getControlClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($componentVariation);
    }

    public function getTitleClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($componentVariation);
    }
    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return getRouteIcon(UsersModuleConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Members', 'poptheme-wassup');
        }

        return parent::getTitle($componentVariation, $props);
    }
    protected function getTitleLink(array $componentVariation, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
                );
                return RequestUtils::addRoute($url, $routes[$componentVariation[1]] ?? null);
        }

        return parent::getTitleLink($componentVariation, $props);
    }
}


