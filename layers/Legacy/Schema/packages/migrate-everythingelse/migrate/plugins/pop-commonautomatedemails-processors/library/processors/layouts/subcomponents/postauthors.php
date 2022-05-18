<?php

class PoPTheme_Wassup_AE_Module_Processor_PostAuthorLayouts extends PoP_Module_Processor_PostAuthorLayoutsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS = 'layout-automatedemails-postauthors';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTAUTHORS:
                $ret[] = [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_ADDONS];
                break;
        }

        return $ret;
    }
}



