<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_URE_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public final const MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS = 'carouselcontrols-stances-byorganizations';
    public final const MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS = 'carouselcontrols-stances-byindividuals';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS],
            [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS],
        );
    }

    public function getControlClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($componentVariation);
    }

    public function getTarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getTarget($componentVariation, $props);
    }
    public function getTitleClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($componentVariation);
    }
    public function getTitle(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).TranslationAPIFacade::getInstance()->__('By organizations', 'pop-userstance-processors');

            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).TranslationAPIFacade::getInstance()->__('By individuals', 'pop-userstance-processors');
        }

        return parent::getTitle($componentVariation, $props);
    }
    protected function getTitleLink(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS);

            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS);
        }

        return parent::getTitleLink($componentVariation, $props);
    }
}


