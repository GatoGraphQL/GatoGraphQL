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

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
            case self::MODULE_CAROUSEL_AUTHOREVENTS:
            case self::MODULE_CAROUSEL_TAGEVENTS:
                $this->appendProp($module, $props, 'class', 'slide');
                $this->appendProp($module, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_EVENTS];

            case self::MODULE_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_AUTHOREVENTS];

            case self::MODULE_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_TAGEVENTS];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getMode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
            case self::MODULE_CAROUSEL_AUTHOREVENTS:
            case self::MODULE_CAROUSEL_TAGEVENTS:
                return 'static';
        }

        return parent::getMode($module, $props);
    }


    public function getControlsTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_EVENTS];

            case self::MODULE_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_AUTHOREVENTS];

            case self::MODULE_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_TAGEVENTS];
        }

        return parent::getControlsTopSubmodule($module);
    }
}


