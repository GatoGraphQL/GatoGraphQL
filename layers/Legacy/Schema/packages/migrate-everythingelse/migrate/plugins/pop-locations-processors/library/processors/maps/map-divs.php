<?php

class PoP_Module_Processor_MapDivs extends PoP_Module_Processor_MapDivsBase
{
    public final const MODULE_MAP_DIV = 'em-map-div';
    public final const MODULE_MAPSTATICIMAGE_DIV = 'em-mapstaticimage-div';
    public final const MODULE_MAPSTATICIMAGE_USERORPOST_DIV = 'em-mapstaticimage-userorpost-div';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MAP_DIV],
            [self::class, self::MODULE_MAPSTATICIMAGE_DIV],
            [self::class, self::MODULE_MAPSTATICIMAGE_USERORPOST_DIV],
        );
    }

    public function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MAPSTATICIMAGE_DIV:
                $ret[] = [PoP_Locations_Module_Processor_CustomScrolls::class, PoP_Locations_Module_Processor_CustomScrolls::MODULE_SCROLL_STATICIMAGE];
                break;

            case self::MODULE_MAPSTATICIMAGE_USERORPOST_DIV:
                $ret[] = [PoP_Locations_Module_Processor_CustomScrolls::class, PoP_Locations_Module_Processor_CustomScrolls::MODULE_SCROLL_STATICIMAGE_USERORPOST];
                break;
        }

        return $ret;
    }
}



