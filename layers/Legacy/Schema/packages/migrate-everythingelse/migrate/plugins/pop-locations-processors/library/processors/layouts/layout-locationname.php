<?php

class PoP_Module_Processor_LocationNameLayouts extends PoP_Module_Processor_LocationNameLayoutsBase
{
    public final const MODULE_EM_LAYOUT_LOCATIONNAME = 'em-layout-locationname';
    public final const MODULE_EM_LAYOUT_LOCATIONICONNAME = 'em-layout-locationiconname';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_LAYOUT_LOCATIONNAME],
            [self::class, self::MODULE_EM_LAYOUT_LOCATIONICONNAME],
        );
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_EM_LAYOUT_LOCATIONICONNAME:
                return 'fa-fw fa-map-marker';
        }
        
        return parent::getFontawesome($module, $props);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($fontawesome = $this->getFontawesome($module, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }
}



