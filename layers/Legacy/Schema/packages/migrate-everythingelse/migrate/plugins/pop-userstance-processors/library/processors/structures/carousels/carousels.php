<?php

class UserStance_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_STANCES = 'carousel-stances';
    public final const MODULE_CAROUSEL_AUTHORSTANCES = 'carousel-authorstances';
    public final const MODULE_CAROUSEL_TAGSTANCES = 'carousel-tagstances';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSEL_STANCES],
            [self::class, self::MODULE_CAROUSEL_AUTHORSTANCES],
            [self::class, self::MODULE_CAROUSEL_TAGSTANCES],
        );
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES:
            case self::MODULE_CAROUSEL_AUTHORSTANCES:
            case self::MODULE_CAROUSEL_TAGSTANCES:
                $this->appendProp($module, $props, 'class', 'slide');
                // $this->appendProp($module, $props, 'class', 'widget widget-info');
                $this->appendProp($module, $props, 'class', 'widget');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_STANCES];

            case self::MODULE_CAROUSEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_AUTHORSTANCES];

            case self::MODULE_CAROUSEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomCarouselInners::class, UserStance_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_TAGSTANCES];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getMode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES:
            case self::MODULE_CAROUSEL_AUTHORSTANCES:
            case self::MODULE_CAROUSEL_TAGSTANCES:
                return 'static';
        }

        return parent::getMode($module, $props);
    }


    public function getControlsTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSEL_STANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_STANCES];

            case self::MODULE_CAROUSEL_AUTHORSTANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_AUTHORSTANCES];

            case self::MODULE_CAROUSEL_TAGSTANCES:
                return [UserStance_Module_Processor_CustomCarouselControls::class, UserStance_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_TAGSTANCES];
        }

        return parent::getControlsTopSubmodule($module);
    }
}


