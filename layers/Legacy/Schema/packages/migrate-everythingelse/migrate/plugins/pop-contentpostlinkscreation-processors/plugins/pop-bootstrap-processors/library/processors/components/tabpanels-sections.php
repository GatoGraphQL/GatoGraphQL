<?php

class PoP_ContentPostLinksCreation_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_MYLINKS = 'tabpanel-mylinks';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABPANEL_MYLINKS,
        );
    }

    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_MYLINKS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
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

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_MYLINKS:
                $ret = array(
                    [
                        'header-subcomponent' => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_TABLE_EDIT],
                    ],
                    [
                        'header-subcomponent' => [PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::class, PoP_ContentPostLinksCreation_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYLINKS_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-subcomponents' =>  array(
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


