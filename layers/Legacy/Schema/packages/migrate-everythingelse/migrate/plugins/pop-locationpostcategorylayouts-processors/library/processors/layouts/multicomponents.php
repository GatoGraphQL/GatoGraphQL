<?php

class PoP_LocationPostCategoryLayouts_Module_Processor_MultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_LOCATIONMAP = 'multicomponent-locationmap';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_LOCATIONMAP],
        );
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_LOCATIONMAP:
                $ret[] = [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::MODULE_MAP_STATICIMAGE_USERORPOST];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_LOCATIONMAP:
                $this->setProp([PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::MODULE_MAP_STATICIMAGE_USERORPOST], $props, 'staticmap-size', '480x150');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



