<?php

class PoP_Core_Module_Processor_FormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_EVERYTHINGQUICKLINKS = 'forminner-everythingquicklinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_EVERYTHINGQUICKLINKS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);
    
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_EVERYTHINGQUICKLINKS:
                $ret[] = [PoP_Module_Processor_FetchlinkTypeaheadFormComponents::class, PoP_Module_Processor_FetchlinkTypeaheadFormComponents::COMPONENT_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING];
                break;
        }

        return $ret;
    }
}



