<?php

class UserStance_URE_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_STANCES_BYORGANIZATIONS = 'tabpanel-stances-byorganizations';
    public final const COMPONENT_TABPANEL_STANCES_BYINDIVIDUALS = 'tabpanel-stances-byindividuals';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABPANEL_STANCES_BYORGANIZATIONS,
            self::COMPONENT_TABPANEL_STANCES_BYINDIVIDUALS,
        );
    }

    protected function getDefaultActivepanelFormat(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_STANCES_BYORGANIZATIONS:
            case self::COMPONENT_TABPANEL_STANCES_BYINDIVIDUALS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_STANCES);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_TABPANEL_STANCES_BYORGANIZATIONS:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_STANCES_BYINDIVIDUALS:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_STANCES_BYORGANIZATIONS:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_STANCES_BYINDIVIDUALS:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


