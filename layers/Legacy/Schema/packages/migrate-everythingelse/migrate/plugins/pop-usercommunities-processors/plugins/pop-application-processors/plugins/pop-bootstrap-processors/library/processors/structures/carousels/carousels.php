<?php

class PoP_UserCommunities_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_AUTHORMEMBERS = 'carousel-authormembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSEL_AUTHORMEMBERS],
        );
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                $this->appendProp($componentVariation, $props, 'class', 'slide');
                $this->appendProp($componentVariation, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                return [PoP_UserCommunities_Module_Processor_CustomCarouselInners::class, PoP_UserCommunities_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_AUTHORMEMBERS];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getMode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                return 'static';
        }

        return parent::getMode($componentVariation, $props);
    }


    public function getControlsTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                return [PoP_UserCommunities_Module_Processor_CustomCarouselControls::class, PoP_UserCommunities_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS];
        }

        return parent::getControlsTopSubmodule($componentVariation);
    }
}


