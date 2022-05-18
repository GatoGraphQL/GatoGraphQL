<?php

class PoP_Module_Processor_CarouselControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL = 'carouselcontrolbuttongroup-carousel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV];
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                // Pass the needed props down the line
                if ($target = $this->getProp($componentVariation, $props, 'carousel-target')) {
                    foreach ($this->getSubComponentVariations($componentVariation) as $submodule) {
                        $this->setProp([$submodule], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                $this->addJsmethod($ret, 'carouselControls');
                break;
        }
        return $ret;
    }
}


