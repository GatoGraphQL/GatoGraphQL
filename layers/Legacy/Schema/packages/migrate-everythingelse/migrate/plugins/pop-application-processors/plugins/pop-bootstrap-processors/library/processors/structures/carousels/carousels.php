<?php

class PoP_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const COMPONENT_CAROUSEL_USERS = 'carousel-users';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CAROUSEL_USERS,
        );
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_USERS:

                $this->appendProp($component, $props, 'class', 'slide');
                $this->appendProp($component, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselInners::class, PoP_Module_Processor_CustomCarouselInners::COMPONENT_CAROUSELINNER_USERS];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getMode(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_USERS:

                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselControls::class, PoP_Module_Processor_CustomCarouselControls::COMPONENT_CAROUSELCONTROLS_USERS];
        }

        return parent::getControlsTopSubcomponent($component);
    }
}


