<?php

class GD_EM_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const COMPONENT_CAROUSEL_EVENTS = 'carousel-events';
    public final const COMPONENT_CAROUSEL_AUTHOREVENTS = 'carousel-authorevents';
    public final const COMPONENT_CAROUSEL_TAGEVENTS = 'carousel-tagevents';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CAROUSEL_EVENTS,
            self::COMPONENT_CAROUSEL_AUTHOREVENTS,
            self::COMPONENT_CAROUSEL_TAGEVENTS,
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_EVENTS:
            case self::COMPONENT_CAROUSEL_AUTHOREVENTS:
            case self::COMPONENT_CAROUSEL_TAGEVENTS:
                $this->appendProp($component, $props, 'class', 'slide');
                $this->appendProp($component, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_EVENTS];

            case self::COMPONENT_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_AUTHOREVENTS];

            case self::COMPONENT_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselInners::class, GD_EM_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_TAGEVENTS];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getMode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_EVENTS:
            case self::COMPONENT_CAROUSEL_AUTHOREVENTS:
            case self::COMPONENT_CAROUSEL_TAGEVENTS:
                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_EVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_EVENTS];

            case self::COMPONENT_CAROUSEL_AUTHOREVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_AUTHOREVENTS];

            case self::COMPONENT_CAROUSEL_TAGEVENTS:
                return [GD_EM_Module_Processor_CustomCarouselControls::class, GD_EM_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_TAGEVENTS];
        }

        return parent::getControlsTopSubcomponent($component);
    }
}


