<?php

class PoP_AddHighlights_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const COMPONENT_BUTTONGROUP_HIGHLIGHTS = 'buttongroup-highlights';
    public final const COMPONENT_BUTTONGROUP_MYHIGHLIGHTS = 'buttongroup-myhighlights';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONGROUP_HIGHLIGHTS],
            [self::class, self::COMPONENT_BUTTONGROUP_MYHIGHLIGHTS],
        );
    }

    protected function getHeadersdataScreen(array $component, array &$props)
    {
        $screens = array(
            self::COMPONENT_BUTTONGROUP_HIGHLIGHTS => POP_SCREEN_HIGHLIGHTS,
            self::COMPONENT_BUTTONGROUP_MYHIGHLIGHTS => POP_SCREEN_MYHIGHLIGHTS,
        );
        if ($screen = $screens[$component[1]] ?? null) {
            return $screen;
        }

        return parent::getHeadersdataScreen($component, $props);
    }
}



