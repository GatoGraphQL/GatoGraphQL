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

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                $this->appendProp($module, $props, 'class', 'slide');
                $this->appendProp($module, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                return [PoP_UserCommunities_Module_Processor_CustomCarouselInners::class, PoP_UserCommunities_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_AUTHORMEMBERS];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getMode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                return 'static';
        }

        return parent::getMode($module, $props);
    }


    public function getControlsTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_AUTHORMEMBERS:
                return [PoP_UserCommunities_Module_Processor_CustomCarouselControls::class, PoP_UserCommunities_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_AUTHORMEMBERS];
        }

        return parent::getControlsTopSubmodule($module);
    }
}


