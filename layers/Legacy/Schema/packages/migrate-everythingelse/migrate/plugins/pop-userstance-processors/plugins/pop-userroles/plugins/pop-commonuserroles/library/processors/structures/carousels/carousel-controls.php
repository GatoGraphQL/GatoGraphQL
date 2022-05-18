<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_URE_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS = 'carouselcontrols-stances-byorganizations';
    public final const MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS = 'carouselcontrols-stances-byindividuals';

    public function getComponentsToProcess(): array
    {
        return array(
            [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS],
            [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS],
        );
    }

    public function getControlClass(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($component);
    }

    public function getTarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getTarget($component, $props);
    }
    public function getTitleClass(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($component);
    }
    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).TranslationAPIFacade::getInstance()->__('By organizations', 'pop-userstance-processors');

            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).TranslationAPIFacade::getInstance()->__('By individuals', 'pop-userstance-processors');
        }

        return parent::getTitle($component, $props);
    }
    protected function getTitleLink(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS);

            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS);
        }

        return parent::getTitleLink($component, $props);
    }
}


