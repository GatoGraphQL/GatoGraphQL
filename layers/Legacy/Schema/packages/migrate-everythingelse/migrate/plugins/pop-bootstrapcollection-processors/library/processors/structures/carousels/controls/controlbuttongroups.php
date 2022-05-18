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

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV];
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                // Pass the needed props down the line
                if ($target = $this->getProp($component, $props, 'carousel-target')) {
                    foreach ($this->getSubComponents($component) as $subComponent) {
                        $this->setProp([$subComponent], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
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


