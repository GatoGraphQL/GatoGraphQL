<?php

class PoP_Module_Processor_CustomFullViewTitleLayouts extends PoP_Module_Processor_FullViewTitleLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEWTITLE = 'layout-fullviewtitle';
    public final const COMPONENT_LAYOUT_PREVIEWPOSTTITLE = 'layout-previewposttitle';
    public final const COMPONENT_LAYOUT_POSTTITLE = 'layout-posttitle';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLVIEWTITLE],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOSTTITLE],
            [self::class, self::COMPONENT_LAYOUT_POSTTITLE],
        );
    }

    public function getHtmlmarkup(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOSTTITLE:
                return 'h4';

            case self::COMPONENT_LAYOUT_POSTTITLE:
                return 'span';
        }
        
        return parent::getHtmlmarkup($component, $props);
    }
}



