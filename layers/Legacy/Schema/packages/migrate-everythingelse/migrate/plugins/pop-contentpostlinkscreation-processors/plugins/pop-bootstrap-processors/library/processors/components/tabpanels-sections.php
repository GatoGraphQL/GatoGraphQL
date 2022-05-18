<?php

class PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_MYLINKS = 'tabpanel-mylinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_MYLINKS],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_MYLINKS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_MYLINKS:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_TABLE_EDIT],
                        [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
                        [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_MYLINKS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_TABLE_EDIT],
                    ],
                    [
                        'header-subcomponent' => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
                            [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );
                break;
        }

        return parent::getPanelHeaders($component, $props);
    }
}


