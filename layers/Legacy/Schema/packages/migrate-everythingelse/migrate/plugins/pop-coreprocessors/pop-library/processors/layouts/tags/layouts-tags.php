<?php

class PoP_Module_Processor_TagLayouts extends PoP_Module_Processor_TagLayoutsBase
{
    public final const MODULE_LAYOUT_TAG = 'layout-tag';
    public final const MODULE_LAYOUT_TAGH4 = 'layout-tagh4';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_TAG],
            [self::class, self::COMPONENT_LAYOUT_TAGH4],
        );
    }

    public function getHtmlTag(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_TAGH4:
                return 'h4';
        }
    
        return parent::getHtmlTag($component, $props);
    }
}


