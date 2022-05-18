<?php

class UserStance_URE_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_STANCES_BYORGANIZATIONS = 'carousel-stances-byorganizations';
    public final const MODULE_CAROUSEL_STANCES_BYINDIVIDUALS = 'carousel-stances-byindividuals';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS],
            [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS],
        );
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                $this->appendProp($module, $props, 'class', 'slide');
                // $this->appendProp($module, $props, 'class', 'widget widget-info');
                $this->appendProp($module, $props, 'class', 'widget');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_STANCES];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getMode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                return 'static';
        }

        return parent::getMode($module, $props);
    }


    public function getControlsTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS];

            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS];
        }

        return parent::getControlsTopSubmodule($module);
    }
}


