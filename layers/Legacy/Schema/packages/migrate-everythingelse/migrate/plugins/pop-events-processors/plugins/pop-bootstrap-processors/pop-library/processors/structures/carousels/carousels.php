<?php

class GD_EM_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_EVENTS = 'carousel-events';
    public final const MODULE_CAROUSEL_AUTHOREVENTS = 'carousel-authorevents';
    public final const MODULE_CAROUSEL_TAGEVENTS = 'carousel-tagevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSEL_EVENTS],
            [self::class, self::MODULE_CAROUSEL_AUTHOREVENTS],
            [self::class, self::MODULE_CAROUSEL_TAGEVENTS],
        );
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
            case self::MODULE_CAROUSEL_AUTHOREVENTS:
            case self::MODULE_CAROUSEL_TAGEVENTS:
                $this->appendProp($component, $props, 'class', 'slide');
                $this->appendProp($component, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_EVENTS];

            case self::MODULE_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_AUTHOREVENTS];

            case self::MODULE_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_TAGEVENTS];
        }

        return parent::getInnerSubmodule($component);
    }

    public function getMode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
            case self::MODULE_CAROUSEL_AUTHOREVENTS:
            case self::MODULE_CAROUSEL_TAGEVENTS:
                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_EVENTS];

            case self::MODULE_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_AUTHOREVENTS];

            case self::MODULE_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_TAGEVENTS];
        }

        return parent::getControlsTopSubmodule($component);
    }
}


