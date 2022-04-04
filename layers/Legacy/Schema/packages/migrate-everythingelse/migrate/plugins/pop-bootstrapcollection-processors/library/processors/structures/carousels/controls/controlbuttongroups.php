<?php

class PoP_Module_Processor_CarouselControlButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL = 'carouselcontrolbuttongroup-carousel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV];
                $ret[] = [PoP_Module_Processor_CarouselButtonControls::class, PoP_Module_Processor_CarouselButtonControls::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                // Pass the needed props down the line
                if ($target = $this->getProp($module, $props, 'carousel-target')) {
                    foreach ($this->getSubmodules($module) as $submodule) {
                        $this->setProp([$submodule], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
                $this->addJsmethod($ret, 'carouselControls');
                break;
        }
        return $ret;
    }
}


