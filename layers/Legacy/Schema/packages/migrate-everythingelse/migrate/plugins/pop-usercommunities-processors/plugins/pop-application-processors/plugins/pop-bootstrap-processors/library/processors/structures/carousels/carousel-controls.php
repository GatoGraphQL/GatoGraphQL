<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\ComponentConfiguration as UsersComponentConfiguration;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_UserCommunities_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_AUTHORMEMBERS = 'carouselcontrols-members';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS],
        );
    }

    public function getControlClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($module);
    }

    public function getTitleClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($module);
    }
    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                return getRouteIcon(UsersComponentConfiguration::getUsersRoute(), true).TranslationAPIFacade::getInstance()->__('Members', 'poptheme-wassup');
        }

        return parent::getTitle($module, $props);
    }
    protected function getTitleLink(array $module, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS => POP_USERCOMMUNITIES_ROUTE_MEMBERS,
                );
                return RequestUtils::addRoute($url, $routes[$module[1]] ?? null);
        }

        return parent::getTitleLink($module, $props);
    }
}


