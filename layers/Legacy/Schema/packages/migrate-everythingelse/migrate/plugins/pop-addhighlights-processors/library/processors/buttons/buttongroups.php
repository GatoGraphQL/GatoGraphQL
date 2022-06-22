<?php

class PoP_AddHighlights_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const COMPONENT_BUTTONGROUP_HIGHLIGHTS = 'buttongroup-highlights';
    public final const COMPONENT_BUTTONGROUP_MYHIGHLIGHTS = 'buttongroup-myhighlights';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONGROUP_HIGHLIGHTS,
            self::COMPONENT_BUTTONGROUP_MYHIGHLIGHTS,
        );
    }

    protected function getHeadersdataScreen(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $screens = array(
            self::COMPONENT_BUTTONGROUP_HIGHLIGHTS => POP_SCREEN_HIGHLIGHTS,
            self::COMPONENT_BUTTONGROUP_MYHIGHLIGHTS => POP_SCREEN_MYHIGHLIGHTS,
        );
        if ($screen = $screens[$component->name] ?? null) {
            return $screen;
        }

        return parent::getHeadersdataScreen($component, $props);
    }
}



