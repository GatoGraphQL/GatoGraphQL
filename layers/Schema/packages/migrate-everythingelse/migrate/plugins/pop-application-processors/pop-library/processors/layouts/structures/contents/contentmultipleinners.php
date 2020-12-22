<?php

class Wassup_Module_Processor_ContentMultipleInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public const MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS = 'contentinnerlayout-highlights';
    public const MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS_APPENDABLE = 'contentinnerlayout-highlights-appendable';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS],
            [self::class, self::MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS_APPENDABLE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS:
                $ret[] = [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT];
                break;

            case self::MODULE_LAYOUTCONTENTINNER_HIGHLIGHTS_APPENDABLE:
                // No need for anything, since this is the layout container, to be filled when the lazyload request comes back
                break;
        }

        return $ret;
    }
}


