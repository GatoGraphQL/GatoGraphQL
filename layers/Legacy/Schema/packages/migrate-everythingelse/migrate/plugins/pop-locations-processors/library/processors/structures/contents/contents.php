<?php

class PoP_Module_Processor_LocationContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_TRIGGERTYPEAHEADSELECT_LOCATION = 'triggertypeaheadselect-location';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TRIGGERTYPEAHEADSELECT_LOCATION],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TRIGGERTYPEAHEADSELECT_LOCATION:
                return [PoP_Module_Processor_LocationContentInners::class, PoP_Module_Processor_LocationContentInners::MODULE_TRIGGERTYPEAHEADSELECTINNER_LOCATION];
        }

        return parent::getInnerSubmodule($module);
    }
}


