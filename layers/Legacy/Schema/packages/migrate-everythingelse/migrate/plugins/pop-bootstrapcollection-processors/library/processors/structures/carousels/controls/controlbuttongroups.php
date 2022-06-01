<?php

class PoP_Module_Processor_CarouselControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL = 'carouselcontrolbuttongroup-carousel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV];
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                // Pass the needed props down the line
                if ($target = $this->getProp($component, $props, 'carousel-target')) {
                    foreach ($this->getSubcomponents($component) as $subcomponent) {
                        $this->setProp([$subcomponent], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                $this->addJsmethod($ret, 'carouselControls');
                break;
        }
        return $ret;
    }
}


