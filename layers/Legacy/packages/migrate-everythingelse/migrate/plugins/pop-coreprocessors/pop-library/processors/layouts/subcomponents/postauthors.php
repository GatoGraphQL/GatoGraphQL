<?php

class PoP_Module_Processor_PostAuthorLayouts extends PoP_Module_Processor_PostAuthorLayoutsBase
{
    public const MODULE_LAYOUT_POSTAUTHORS = 'layout-postauthors';
    public const MODULE_LAYOUT_SIMPLEPOSTAUTHORS = 'layout-simplepostauthors';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTAUTHORS],
            [self::class, self::MODULE_LAYOUT_SIMPLEPOSTAUTHORS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTAUTHORS:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_POSTAUTHOR];
                break;
            
            case self::MODULE_LAYOUT_SIMPLEPOSTAUTHORS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_USER_AVATAR26];
                break;
        }

        return $ret;
    }
}



