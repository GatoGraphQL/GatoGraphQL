<?php

class UserStance_Module_Processor_SingleSectionTabPanelComponents extends PoP_Module_Processor_SingleSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT = 'tabpanel-singlerelatedstancecontent';
    public final const COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO = 'tabpanel-singlerelatedstancecontent-pro';
    public final const COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST = 'tabpanel-singlerelatedstancecontent-against';
    public final const COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL = 'tabpanel-singlerelatedstancecontent-neutral';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT],
            [self::class, self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO],
            [self::class, self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST],
            [self::class, self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT:
            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_SINGLESTANCES);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_PRO:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_PRO_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_AGAINST:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_AGAINST_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_SINGLERELATEDSTANCECONTENT_NEUTRAL:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_SINGLERELATEDSTANCECONTENT_NEUTRAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


