<?php

class GD_EM_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_EVENTS = 'carousel-events';
    public final const MODULE_CAROUSEL_AUTHOREVENTS = 'carousel-authorevents';
    public final const MODULE_CAROUSEL_TAGEVENTS = 'carousel-tagevents';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSEL_EVENTS],
            [self::class, self::MODULE_CAROUSEL_AUTHOREVENTS],
            [self::class, self::MODULE_CAROUSEL_TAGEVENTS],
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
            case self::MODULE_CAROUSEL_AUTHOREVENTS:
            case self::MODULE_CAROUSEL_TAGEVENTS:
                $this->appendProp($componentVariation, $props, 'class', 'slide');
                $this->appendProp($componentVariation, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_EVENTS];

            case self::MODULE_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_AUTHOREVENTS];

            case self::MODULE_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_TAGEVENTS];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getMode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
            case self::MODULE_CAROUSEL_AUTHOREVENTS:
            case self::MODULE_CAROUSEL_TAGEVENTS:
                return 'static';
        }

        return parent::getMode($componentVariation, $props);
    }


    public function getControlsTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_EVENTS];

            case self::MODULE_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_AUTHOREVENTS];

            case self::MODULE_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_TAGEVENTS];
        }

        return parent::getControlsTopSubmodule($componentVariation);
    }
}


