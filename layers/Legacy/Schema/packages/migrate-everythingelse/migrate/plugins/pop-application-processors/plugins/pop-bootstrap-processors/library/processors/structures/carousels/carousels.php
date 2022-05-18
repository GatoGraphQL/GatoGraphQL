<?php

class PoP_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_USERS = 'carousel-users';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSEL_USERS],
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_USERS:

                $this->appendProp($componentVariation, $props, 'class', 'slide');
                $this->appendProp($componentVariation, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselInners::class, PoP_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_USERS];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getMode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_USERS:

                return 'static';
        }

        return parent::getMode($componentVariation, $props);
    }


    public function getControlsTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselControls::class, PoP_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_USERS];
        }

        return parent::getControlsTopSubmodule($componentVariation);
    }
}


