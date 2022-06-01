<?php

class PoP_Module_Processor_CarouselControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CAROUSELCONTROLGROUP_CAROUSEL = 'carouselcontrolgroup-carousel';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CAROUSELCONTROLGROUP_CAROUSEL,
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_CAROUSELCONTROLGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselControlButtonGroups::class, PoP_Module_Processor_CarouselControlButtonGroups::COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSELCONTROLGROUP_CAROUSEL:
                if ($target = $this->getProp($component, $props, 'carousel-target')) {
                    foreach ($this->getSubcomponents($component) as $subcomponent) {
                        $this->setProp([$subcomponent], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


