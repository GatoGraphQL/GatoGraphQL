<?php

class PoP_LocationPostsCreation_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_MYLOCATIONPOSTS = 'tabpanel-mylocationposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_MYLOCATIONPOSTS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYLOCATIONPOSTS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYLOCATIONPOSTS:
                $ret = array_merge(
                    $ret,
                    array(
                        [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLOCATIONPOSTS_TABLE_EDIT],
                        [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
                        [GD_Custom_EM_Module_Processor_MySectionDataloads::class, GD_Custom_EM_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        return $ret;
    }
}


