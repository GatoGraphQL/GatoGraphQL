<?php

class UserStance_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const COMPONENT_CAROUSEL_STANCES = 'carousel-stances';
    public final const COMPONENT_CAROUSEL_AUTHORSTANCES = 'carousel-authorstances';
    public final const COMPONENT_CAROUSEL_TAGSTANCES = 'carousel-tagstances';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSEL_STANCES],
            [self::class, self::COMPONENT_CAROUSEL_AUTHORSTANCES],
            [self::class, self::COMPONENT_CAROUSEL_TAGSTANCES],
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES:
            case self::COMPONENT_CAROUSEL_AUTHORSTANCES:
            case self::COMPONENT_CAROUSEL_TAGSTANCES:
                $this->appendProp($component, $props, 'class', 'slide');
                // $this->appendProp($component, $props, 'class', 'widget widget-info');
                $this->appendProp($component, $props, 'class', 'widget');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_STANCES];

            case self::COMPONENT_CAROUSEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_AUTHORSTANCES];

            case self::COMPONENT_CAROUSEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_TAGSTANCES];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getMode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES:
            case self::COMPONENT_CAROUSEL_AUTHORSTANCES:
            case self::COMPONENT_CAROUSEL_TAGSTANCES:
                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_STANCES];

            case self::COMPONENT_CAROUSEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_AUTHORSTANCES];

            case self::COMPONENT_CAROUSEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_TAGSTANCES];
        }

        return parent::getControlsTopSubcomponent($component);
    }
}


