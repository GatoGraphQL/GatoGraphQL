<?php

class PoP_Core_Module_Processor_FormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_EVERYTHINGQUICKLINKS = 'forminner-everythingquicklinks';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_EVERYTHINGQUICKLINKS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_EVERYTHINGQUICKLINKS:
                $ret[] = [PoP_Module_Processor_FetchlinkTypeaheadFormComponents::class, PoP_Module_Processor_FetchlinkTypeaheadFormComponents::COMPONENT_FORMCOMPONENT_QUICKLINKTYPEAHEAD_EVERYTHING];
                break;
        }

        return $ret;
    }
}



