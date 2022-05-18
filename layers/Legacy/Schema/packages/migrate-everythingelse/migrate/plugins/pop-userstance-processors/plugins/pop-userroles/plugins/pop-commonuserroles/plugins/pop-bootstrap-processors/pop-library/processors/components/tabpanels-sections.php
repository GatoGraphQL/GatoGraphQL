<?php

class UserStance_URE_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_STANCES_BYORGANIZATIONS = 'tabpanel-stances-byorganizations';
    public final const MODULE_TABPANEL_STANCES_BYINDIVIDUALS = 'tabpanel-stances-byindividuals';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_STANCES_BYORGANIZATIONS],
            [self::class, self::MODULE_TABPANEL_STANCES_BYINDIVIDUALS],
        );
    }

    protected function getDefaultActivepanelFormat(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TABPANEL_STANCES_BYORGANIZATIONS:
            case self::MODULE_TABPANEL_STANCES_BYINDIVIDUALS:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_STANCES);
        }

        return parent::getDefaultActivepanelFormat($componentVariation);
    }

    public function getPanelSubmodules(array $componentVariation)
    {
        $ret = parent::getPanelSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_TABPANEL_STANCES_BYORGANIZATIONS:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::MODULE_TABPANEL_STANCES_BYINDIVIDUALS:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                        [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_TABPANEL_STANCES_BYORGANIZATIONS:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_LIST],
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYORGANIZATIONS_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_STANCES_BYINDIVIDUALS:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_LIST],
                            [UserStance_URE_Module_Processor_CustomSectionDataloads::class, UserStance_URE_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_STANCES_BYINDIVIDUALS_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($componentVariation, $props);
    }
}


