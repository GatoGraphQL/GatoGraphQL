<?php

class PoP_Module_Processor_CarouselControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_CAROUSELCONTROLGROUP_CAROUSEL = 'carouselcontrolgroup-carousel';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL:
                $ret[] = [PoP_Module_Processor_CarouselControlButtonGroups::class, PoP_Module_Processor_CarouselControlButtonGroups::MODULE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELCONTROLGROUP_CAROUSEL:
                if ($target = $this->getProp($module, $props, 'carousel-target')) {
                    foreach ($this->getSubmodules($module) as $submodule) {
                        $this->setProp([$submodule], $props, 'carousel-target', $target);
                    }
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


