<?php

class GD_URE_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_ORGANIZATIONS = 'tabpanel-organizations';
    public final const COMPONENT_TABPANEL_INDIVIDUALS = 'tabpanel-individuals';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_ORGANIZATIONS],
            [self::class, self::COMPONENT_TABPANEL_INDIVIDUALS],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_ORGANIZATIONS:
            case self::COMPONENT_TABPANEL_INDIVIDUALS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_ORGANIZATIONS:
                $ret = array_merge(
                    $ret,
                    array(
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
                        [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLLMAP],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_INDIVIDUALS:
                $ret = array_merge(
                    $ret,
                    array(
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
                        [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST],
                        [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLLMAP],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_ORGANIZATIONS:
                return array(
                    [
                        'header-subcomponent' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_DETAILS],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_THUMBNAIL],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-subcomponent' => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_ORGANIZATIONS_SCROLLMAP],
                    ],
                );

            case self::COMPONENT_TABPANEL_INDIVIDUALS:
                return array(
                    [
                        'header-subcomponent' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
                        'subheader-subcomponents' =>  array(
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_DETAILS],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_THUMBNAIL],
                            [GD_URE_Module_Processor_CustomSectionDataloads::class, GD_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLL_LIST],
                        ),
                    ],
                    [
                        'header-subcomponent' => [GD_URE_Module_Processor_CustomScrollMapSectionDataloads::class, GD_URE_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_INDIVIDUALS_SCROLLMAP],
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}


