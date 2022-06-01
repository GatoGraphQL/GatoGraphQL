<?php

class PoP_UserCommunities_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const COMPONENT_CAROUSEL_AUTHORMEMBERS = 'carousel-authormembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSEL_AUTHORMEMBERS],
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_AUTHORMEMBERS:
                $this->appendProp($component, $props, 'class', 'slide');
                $this->appendProp($component, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_AUTHORMEMBERS:
                return [PoP_UserCommunities_Module_Processor_CustomCarouselInners::class, PoP_UserCommunities_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_AUTHORMEMBERS];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getMode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_AUTHORMEMBERS:
                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_AUTHORMEMBERS:
                return [PoP_UserCommunities_Module_Processor_CustomCarouselControls::class, PoP_UserCommunities_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_AUTHORMEMBERS];
        }

        return parent::getControlsTopSubcomponent($component);
    }
}


