<?php

class UserStance_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_STANCES = 'carousel-stances';
    public final const MODULE_CAROUSEL_AUTHORSTANCES = 'carousel-authorstances';
    public final const MODULE_CAROUSEL_TAGSTANCES = 'carousel-tagstances';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSEL_STANCES],
            [self::class, self::MODULE_CAROUSEL_AUTHORSTANCES],
            [self::class, self::MODULE_CAROUSEL_TAGSTANCES],
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_STANCES:
            case self::MODULE_CAROUSEL_AUTHORSTANCES:
            case self::MODULE_CAROUSEL_TAGSTANCES:
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
            case self::MODULE_CAROUSEL_STANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_STANCES];

            case self::MODULE_CAROUSEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_AUTHORSTANCES];

            case self::MODULE_CAROUSEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_TAGSTANCES];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getMode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_STANCES:
            case self::MODULE_CAROUSEL_AUTHORSTANCES:
            case self::MODULE_CAROUSEL_TAGSTANCES:
                return 'static';
        }

        return parent::getMode($componentVariation, $props);
    }


    public function getControlsTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_STANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES];

            case self::MODULE_CAROUSEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_AUTHORSTANCES];

            case self::MODULE_CAROUSEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_TAGSTANCES];
        }

        return parent::getControlsTopSubmodule($componentVariation);
    }
}


