<?php

class PoP_Module_Processor_CarouselControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CAROUSELCONTROLGROUP_CAROUSEL = 'carouselcontrolgroup-carousel';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselControlButtonGroups::class, PoP_Module_Processor_CarouselControlButtonGroups::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL:
                if ($target = $this->getProp($component, $props, 'carousel-target')) {
                    foreach ($this->getSubComponents($component) as $subComponent) {
                        $this->setProp([$subComponent], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


