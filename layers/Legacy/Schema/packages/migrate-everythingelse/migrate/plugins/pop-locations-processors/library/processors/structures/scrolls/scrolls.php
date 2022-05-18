<?php

class PoP_Locations_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_LOCATIONS = 'scroll-locations';
    public final const MODULE_SCROLL_STATICIMAGE = 'scroll-staticimage';
    public final const MODULE_SCROLL_STATICIMAGE_USERORPOST = 'scroll-staticimage-userorpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_LOCATIONS],
            [self::class, self::MODULE_SCROLL_STATICIMAGE],
            [self::class, self::MODULE_SCROLL_STATICIMAGE_USERORPOST],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_LOCATIONS => [PoP_Locations_Module_Processor_CustomScrollInners::class, PoP_Locations_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LOCATIONS],
            self::MODULE_SCROLL_STATICIMAGE => [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::MODULE_MAP_STATICIMAGE],
            self::MODULE_SCROLL_STATICIMAGE_USERORPOST => [PoP_Module_Processor_MapStaticImages::class, PoP_Module_Processor_MapStaticImages::MODULE_MAP_STATICIMAGE_USERORPOST],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}


