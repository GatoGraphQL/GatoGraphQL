<?php

class PoP_Module_Processor_CustomFullViewTitleLayouts extends PoP_Module_Processor_FullViewTitleLayoutsBase
{
    public final const MODULE_LAYOUT_FULLVIEWTITLE = 'layout-fullviewtitle';
    public final const MODULE_LAYOUT_PREVIEWPOSTTITLE = 'layout-previewposttitle';
    public final const MODULE_LAYOUT_POSTTITLE = 'layout-posttitle';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEWTITLE],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOSTTITLE],
            [self::class, self::MODULE_LAYOUT_POSTTITLE],
        );
    }

    public function getHtmlmarkup(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOSTTITLE:
                return 'h4';

            case self::MODULE_LAYOUT_POSTTITLE:
                return 'span';
        }
        
        return parent::getHtmlmarkup($module, $props);
    }
}



