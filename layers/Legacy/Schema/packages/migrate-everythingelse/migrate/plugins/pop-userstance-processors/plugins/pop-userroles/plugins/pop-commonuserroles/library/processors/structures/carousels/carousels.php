<?php

class UserStance_URE_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS = 'carousel-stances-byorganizations';
    public final const COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS = 'carousel-stances-byindividuals';

    public function getComponentsToProcess(): array
    {
        return array(
            [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS],
            [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS],
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                $this->appendProp($component, $props, 'class', 'slide');
                // $this->appendProp($component, $props, 'class', 'widget widget-info');
                $this->appendProp($component, $props, 'class', 'widget');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_STANCES];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getMode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS];

            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS];
        }

        return parent::getControlsTopSubcomponent($component);
    }
}


