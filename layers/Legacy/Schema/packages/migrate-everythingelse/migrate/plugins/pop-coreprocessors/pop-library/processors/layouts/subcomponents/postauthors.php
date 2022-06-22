<?php

class PoP_Module_Processor_PostAuthorLayouts extends PoP_Module_Processor_PostAuthorLayoutsBase
{
    public final const COMPONENT_LAYOUT_POSTAUTHORS = 'layout-postauthors';
    public final const COMPONENT_LAYOUT_SIMPLEPOSTAUTHORS = 'layout-simplepostauthors';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_POSTAUTHORS,
            self::COMPONENT_LAYOUT_SIMPLEPOSTAUTHORS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_POSTAUTHORS:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_POSTAUTHOR];
                break;
            
            case self::COMPONENT_LAYOUT_SIMPLEPOSTAUTHORS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::COMPONENT_LAYOUT_POPOVER_USER_AVATAR26];
                break;
        }

        return $ret;
    }
}



