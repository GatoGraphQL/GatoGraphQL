<?php

class PoP_Module_Processor_TagLayouts extends PoP_Module_Processor_TagLayoutsBase
{
    public final const MODULE_LAYOUT_TAG = 'layout-tag';
    public final const MODULE_LAYOUT_TAGH4 = 'layout-tagh4';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_TAG],
            [self::class, self::MODULE_LAYOUT_TAGH4],
        );
    }

    public function getHtmlTag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_TAGH4:
                return 'h4';
        }
    
        return parent::getHtmlTag($module, $props);
    }
}


