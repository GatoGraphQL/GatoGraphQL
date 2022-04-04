<?php

class PoP_Core_Module_Processor_FormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_EVERYTHINGQUICKLINKS = 'forminner-everythingquicklinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_EVERYTHINGQUICKLINKS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_FORMINNER_EVERYTHINGQUICKLINKS:
                $ret[] = [PoP_Module_Processor_FetchlinkTypeaheadFormComponents::class, PoP_Module_Processor_FetchlinkTypeaheadFormComponents::MODULE_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING];
                break;
        }

        return $ret;
    }
}



