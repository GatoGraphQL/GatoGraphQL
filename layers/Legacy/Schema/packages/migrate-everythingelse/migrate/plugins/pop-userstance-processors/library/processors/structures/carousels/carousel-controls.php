<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class UserStance_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const COMPONENT_CAROUSELCONTROLS_STANCES = 'carouselcontrols-stances';
    public final const COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES = 'carouselcontrols-authorstances';
    public final const COMPONENT_CAROUSELCONTROLS_TAGSTANCES = 'carouselcontrols-tagstances';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSELCONTROLS_STANCES],
            [self::class, self::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES],
            [self::class, self::COMPONENT_CAROUSELCONTROLS_TAGSTANCES],
        );
    }

    public function getControlClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_STANCES:
            case self::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES:
            case self::COMPONENT_CAROUSELCONTROLS_TAGSTANCES:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($component);
    }

    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_STANCES:
            case self::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES:
            case self::COMPONENT_CAROUSELCONTROLS_TAGSTANCES:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getTarget($component, $props);
    }
    public function getTitleClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_STANCES:
            case self::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES:
            case self::COMPONENT_CAROUSELCONTROLS_TAGSTANCES:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($component);
    }
    public function getTitle(array $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_STANCES:
                return RouteUtils::getRouteTitle(POP_USERSTANCE_ROUTE_STANCES);

            case self::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);

                // Allow URE to override adding "+Members"
                $name = \PoP\Root\App::applyFilters(
                    'UserStance_Module_Processor_CustomCarouselControls:authorstances:title',
                    $userTypeAPI->getUserDisplayName($author)
                );
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('%s by %s', 'pop-userstance-processors'),
                    getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true) . PoP_UserStanceProcessors_Utils::getLatestvotesTitle(),
                    $name
                );

            case self::COMPONENT_CAROUSELCONTROLS_TAGSTANCES:
                $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return sprintf(
                    TranslationAPIFacade::getInstance()->__('%s tagged with “%s”', 'pop-userstance-processors'),
                    getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true) . PoP_UserStanceProcessors_Utils::getLatestvotesTitle(),
                    $applicationtaxonomyapi->getTagSymbolName($tag_id)
                );
        }

        return parent::getTitle($component, $props);
    }
    protected function getTitleLink(array $component, array &$props)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLS_STANCES:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES);

            case self::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $url = $userTypeAPI->getUserURL($author);
                $routes = array(
                    self::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES => POP_USERSTANCE_ROUTE_STANCES,
                );

                // Allow URE to override adding "+Members" param
                return \PoP\Root\App::applyFilters(
                    'UserStance_Module_Processor_CustomCarouselControls:authorstances:titlelink',
                    RequestUtils::addRoute($url, $routes[$component[1]])
                );

            case self::COMPONENT_CAROUSELCONTROLS_TAGSTANCES:
                $url = $postTagTypeAPI->getTagURL(\PoP\Root\App::getState(['routing', 'queried-object-id']));
                $routes = array(
                    self::COMPONENT_CAROUSELCONTROLS_TAGSTANCES => POP_USERSTANCE_ROUTE_STANCES,
                );

                return \PoP\Root\App::applyFilters(
                    'UserStance_Module_Processor_CustomCarouselControls:tagstances:titlelink',
                    RequestUtils::addRoute($url, $routes[$component[1]])
                );
        }

        return parent::getTitleLink($component, $props);
    }
}


