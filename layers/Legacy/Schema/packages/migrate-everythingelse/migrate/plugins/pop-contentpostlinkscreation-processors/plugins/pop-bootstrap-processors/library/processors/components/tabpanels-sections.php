<?php

class PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_MYLINKS = 'tabpanel-mylinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_MYLINKS],
        );
    }

    protected function getDefaultActivepanelFormat(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYLINKS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }

        return parent::getDefaultActivepanelFormat($module);
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYLINKS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_TABLE_EDIT],
                        [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_MYLINKS:
                $ret = array(
                    [
                        'header-submodule' => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($module, $props);
    }
}


