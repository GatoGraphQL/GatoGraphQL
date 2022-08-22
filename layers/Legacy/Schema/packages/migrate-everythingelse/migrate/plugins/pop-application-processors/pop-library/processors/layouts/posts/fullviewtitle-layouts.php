<?php

class PoP_Module_Processor_CustomFullViewTitleLayouts extends PoP_Module_Processor_FullViewTitleLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEWTITLE = 'layout-fullviewtitle';
    public final const COMPONENT_LAYOUT_PREVIEWPOSTTITLE = 'layout-previewposttitle';
    public final const COMPONENT_LAYOUT_POSTTITLE = 'layout-posttitle';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FULLVIEWTITLE,
            self::COMPONENT_LAYOUT_PREVIEWPOSTTITLE,
            self::COMPONENT_LAYOUT_POSTTITLE,
        );
    }

    public function getHtmlmarkup(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOSTTITLE:
                return 'h4';

            case self::COMPONENT_LAYOUT_POSTTITLE:
                return 'span';
        }
        
        return parent::getHtmlmarkup($component, $props);
    }
}



