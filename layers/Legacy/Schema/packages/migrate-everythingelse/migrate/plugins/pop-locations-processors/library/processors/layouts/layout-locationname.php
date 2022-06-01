<?php

class PoP_Module_Processor_LocationNameLayouts extends PoP_Module_Processor_LocationNameLayoutsBase
{
    public final const COMPONENT_EM_LAYOUT_LOCATIONNAME = 'em-layout-locationname';
    public final const COMPONENT_EM_LAYOUT_LOCATIONICONNAME = 'em-layout-locationiconname';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_LAYOUT_LOCATIONNAME,
            self::COMPONENT_EM_LAYOUT_LOCATIONICONNAME,
        );
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_EM_LAYOUT_LOCATIONICONNAME:
                return 'fa-fw fa-map-marker';
        }
        
        return parent::getFontawesome($component, $props);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($fontawesome = $this->getFontawesome($component, $props)) {
            $ret[GD_JS_FONTAWESOME] = $fontawesome;
        }

        return $ret;
    }
}



