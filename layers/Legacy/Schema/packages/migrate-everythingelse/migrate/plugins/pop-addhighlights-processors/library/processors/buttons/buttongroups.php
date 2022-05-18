<?php

class PoP_AddHighlights_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public final const MODULE_BUTTONGROUP_HIGHLIGHTS = 'buttongroup-highlights';
    public final const MODULE_BUTTONGROUP_MYHIGHLIGHTS = 'buttongroup-myhighlights';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONGROUP_HIGHLIGHTS],
            [self::class, self::MODULE_BUTTONGROUP_MYHIGHLIGHTS],
        );
    }

    protected function getHeadersdataScreen(array $componentVariation, array &$props)
    {
        $screens = array(
            self::MODULE_BUTTONGROUP_HIGHLIGHTS => POP_SCREEN_HIGHLIGHTS,
            self::MODULE_BUTTONGROUP_MYHIGHLIGHTS => POP_SCREEN_MYHIGHLIGHTS,
        );
        if ($screen = $screens[$componentVariation[1]] ?? null) {
            return $screen;
        }

        return parent::getHeadersdataScreen($componentVariation, $props);
    }
}



