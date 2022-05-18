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

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_USERS:

                $this->appendProp($module, $props, 'class', 'slide');
                $this->appendProp($module, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselInners::class, PoP_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_USERS];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getMode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_USERS:

                return 'static';
        }

        return parent::getMode($module, $props);
    }


    public function getControlsTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselControls::class, PoP_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_USERS];
        }

        return parent::getControlsTopSubmodule($module);
    }
}


