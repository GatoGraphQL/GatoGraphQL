<?php

class UserStance_Module_Processor_ContentMultipleInners extends PoP_Module_Processor_ContentMultipleInnersBase
{
    public const MODULE_LAYOUTCONTENTINNER_STANCES = 'contentinnerlayout-stances';
    public const MODULE_LAYOUTCONTENTINNER_STANCES_APPENDABLE = 'contentinnerlayout-stances-appendable';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTCONTENTINNER_STANCES],
            [self::class, self::MODULE_LAYOUTCONTENTINNER_STANCES_APPENDABLE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTCONTENTINNER_STANCES:
                $ret[] = [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED];
                break;

            case self::MODULE_LAYOUTCONTENTINNER_STANCES_APPENDABLE:
                // No need for anything, since this is the layout container, to be filled when the lazyload request comes back
                break;
        }

        return $ret;
    }
}


