<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_URE_Module_Processor_CustomCarouselControls extends PoP_Module_Processor_CarouselControlsBase
{
    public const MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS = 'carouselcontrols-stances-byorganizations';
    public const MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS = 'carouselcontrols-stances-byindividuals';

    public function getModulesToProcess(): array
    {
        return array(
            [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS],
            [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS],
        );
    }

    public function getControlClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return 'btn btn-link btn-compact';
        }

        return parent::getControlClass($module);
    }

    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getTarget($module, $props);
    }
    public function getTitleClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return 'btn btn-link btn-compact';
        }

        return parent::getTitleClass($module);
    }
    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).TranslationAPIFacade::getInstance()->__('By organizations', 'pop-userstance-processors');

            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, true).TranslationAPIFacade::getInstance()->__('By individuals', 'pop-userstance-processors');
        }

        return parent::getTitle($module, $props);
    }
    protected function getTitleLink(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS);

            case self::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS:
                return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS);
        }

        return parent::getTitleLink($module, $props);
    }
}


