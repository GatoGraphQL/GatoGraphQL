<?php

class PoP_Module_Processor_TagLayouts extends PoP_Module_Processor_TagLayoutsBase
{
    public final const COMPONENT_LAYOUT_TAG = 'layout-tag';
    public final const COMPONENT_LAYOUT_TAGH4 = 'layout-tagh4';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_TAG,
            self::COMPONENT_LAYOUT_TAGH4,
        );
    }

    public function getHtmlTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_TAGH4:
                return 'h4';
        }
    
        return parent::getHtmlTag($component, $props);
    }
}


