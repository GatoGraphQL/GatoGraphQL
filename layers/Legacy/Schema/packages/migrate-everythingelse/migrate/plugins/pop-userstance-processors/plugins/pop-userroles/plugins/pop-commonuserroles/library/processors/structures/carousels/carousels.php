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

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                $this->appendProp($componentVariation, $props, 'class', 'slide');
                // $this->appendProp($componentVariation, $props, 'class', 'widget widget-info');
                $this->appendProp($componentVariation, $props, 'class', 'widget');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_STANCES];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getMode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                return 'static';
        }

        return parent::getMode($componentVariation, $props);
    }


    public function getControlsTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_STANCES_BYORGANIZATIONS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS];

            case self::MODULE_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS];
        }

        return parent::getControlsTopSubmodule($componentVariation);
    }
}


