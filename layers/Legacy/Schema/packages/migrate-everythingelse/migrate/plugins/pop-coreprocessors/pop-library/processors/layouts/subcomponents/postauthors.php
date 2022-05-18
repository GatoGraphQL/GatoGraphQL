<?php

class PoP_Module_Processor_PostAuthorLayouts extends PoP_Module_Processor_PostAuthorLayoutsBase
{
    public final const MODULE_LAYOUT_POSTAUTHORS = 'layout-postauthors';
    public final const MODULE_LAYOUT_SIMPLEPOSTAUTHORS = 'layout-simplepostauthors';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTAUTHORS],
            [self::class, self::COMPONENT_LAYOUT_SIMPLEPOSTAUTHORS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
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



