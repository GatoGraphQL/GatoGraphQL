<?php

class UserStance_URE_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_STANCES_BYORGANIZATIONS = 'carousel-stances-byorganizations';
    public final const MODULE_CAROUSEL_STANCES_BYINDIVIDUALS = 'carousel-stances-byindividuals';

    public function getComponentsToProcess(): array
    {
        return array(
            [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS],
            [UserStance_Module_Processor_CustomCarousels::class, UserStance_Module_Processor_CustomCarousels::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS],
        );
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                $this->appendProp($component, $props, 'class', 'slide');
                // $this->appendProp($component, $props, 'class', 'widget widget-info');
                $this->appendProp($component, $props, 'class', 'widget');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_STANCES];
        }

        return parent::getInnerSubmodule($component);
    }

    public function getMode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSEL_STANCES_BYORGANIZATIONS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_STANCES_BYORGANIZATIONS];

            case self::COMPONENT_CAROUSEL_STANCES_BYINDIVIDUALS:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_STANCES_BYINDIVIDUALS];
        }

        return parent::getControlsTopSubmodule($component);
    }
}


