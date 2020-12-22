<?php

class PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTICOMPONENT_LOCATIONMAP = 'multicomponent-locationmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_LOCATIONMAP],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_LOCATIONMAP:
                $ret[] = [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::MODULE_MAP_STATICIMAGE_USERORPOST];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_LOCATIONMAP:
                $this->setProp([PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::MODULE_MAP_STATICIMAGE_USERORPOST], $props, 'staticmap-size', '480x150');
                break;
        }
        
        parent::initModelProps($module, $props);
    }
}



