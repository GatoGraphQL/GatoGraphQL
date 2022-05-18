<?php

class PoP_Module_Processor_CarouselControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CAROUSELCONTROLGROUP_CAROUSEL = 'carouselcontrolgroup-carousel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselControlButtonGroups::class, PoP_Module_Processor_CarouselControlButtonGroups::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL:
                if ($target = $this->getProp($componentVariation, $props, 'carousel-target')) {
                    foreach ($this->getSubComponentVariations($componentVariation) as $submodule) {
                        $this->setProp([$submodule], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


