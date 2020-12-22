<?php

class PoP_AddHighlights_Module_Processor_ButtonGroups extends PoP_Module_Processor_CustomButtonGroupsBase
{
    public const MODULE_BUTTONGROUP_HIGHLIGHTS = 'buttongroup-highlights';
    public const MODULE_BUTTONGROUP_MYHIGHLIGHTS = 'buttongroup-myhighlights';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONGROUP_HIGHLIGHTS],
            [self::class, self::MODULE_BUTTONGROUP_MYHIGHLIGHTS],
        );
    }

    protected function getHeadersdataScreen(array $module, array &$props)
    {
        $screens = array(
            self::MODULE_BUTTONGROUP_HIGHLIGHTS => POP_SCREEN_HIGHLIGHTS,
            self::MODULE_BUTTONGROUP_MYHIGHLIGHTS => POP_SCREEN_MYHIGHLIGHTS,
        );
        if ($screen = $screens[$module[1]]) {
            return $screen;
        }

        return parent::getHeadersdataScreen($module, $props);
    }
}



