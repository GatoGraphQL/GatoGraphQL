<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\RequestUtils;

class UserStance_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public const MODULE_CAROUSELCONTROLS_STANCES = 'carouselcontrols-stances';
    public const MODULE_CAROUSELCONTROLS_AUTHORSTANCES = 'carouselcontrols-authorstances';
    public const MODULE_CAROUSELCONTROLS_TAGSTANCES = 'carouselcontrols-tagstances';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLS_STANCES],
            [self::class, self::MODULE_CAROUSELCONTROLS_AUTHORSTANCES],
            [self::class, self::MODULE_CAROUSELCONTROLS_TAGSTANCES],
        );
    }

    public function getControlClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES:
            case self::MODULE_CAROUSELCONTROLS_AUTHORSTANCES:
            case self::MODULE_CAROUSELCONTROLS_TAGSTANCES:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($module);
    }

    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES:
            case self::MODULE_CAROUSELCONTROLS_AUTHORSTANCES:
            case self::MODULE_CAROUSELCONTROLS_TAGSTANCES:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getTarget($module, $props);
    }
    public function getTitleClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES:
            case self::MODULE_CAROUSELCONTROLS_AUTHORSTANCES:
            case self::MODULE_CAROUSELCONTROLS_TAGSTANCES:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($module);
    }
    public function getTitle(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES:
                return RouteUtils::getRouteTitle(POP_USERSTANCE_ROUTE_STANCES);

            case self::MODULE_CAROUSELCONTROLS_AUTHORSTANCES:
                $author = $vars['routing-state']['queried-object-id'];

                // Allow URE to override adding "+Members"
                $name = HooksAPIFacade::getInstance()->applyFilters(
                    'UserStance_Module_Processor_CustomCarouselControls:authorstances:title',
                    $cmsusersapi->getUserDisplayName($author)
                );
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('%s by %s', 'pop-userstance-processors'),
                    getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true) . PoP_UserStanceProcessors_Utils::getLatestvotesTitle(),
                    $name
                );

            case self::MODULE_CAROUSELCONTROLS_TAGSTANCES:
                $tag_id = $vars['routing-state']['queried-object-id'];
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('%s tagged with “%s”', 'pop-userstance-processors'),
                    getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true) . PoP_UserStanceProcessors_Utils::getLatestvotesTitle(),
                    $applicationtaxonomyapi->getTagSymbolName($tag_id)
                );
        }

        return parent::getTitle($module, $props);
    }
    protected function getTitleLink(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $posttagapi = \PoPSchema\PostTags\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES);

            case self::MODULE_CAROUSELCONTROLS_AUTHORSTANCES:
                $author = $vars['routing-state']['queried-object-id'];
                $url = $cmsusersapi->getUserURL($author);
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_AUTHORSTANCES => POP_USERSTANCE_ROUTE_STANCES,
                );

                // Allow URE to override adding "+Members" param
                return HooksAPIFacade::getInstance()->applyFilters(
                    'UserStance_Module_Processor_CustomCarouselControls:authorstances:titlelink',
                    RequestUtils::addRoute($url, $routes[$module[1]])
                );

            case self::MODULE_CAROUSELCONTROLS_TAGSTANCES:
                $url = $posttagapi->getTagLink($vars['routing-state']['queried-object-id']);
                $routes = array(
                    self::MODULE_CAROUSELCONTROLS_TAGSTANCES => POP_USERSTANCE_ROUTE_STANCES,
                );

                return HooksAPIFacade::getInstance()->applyFilters(
                    'UserStance_Module_Processor_CustomCarouselControls:tagstances:titlelink',
                    RequestUtils::addRoute($url, $routes[$module[1]])
                );
        }

        return parent::getTitleLink($module, $props);
    }
}


