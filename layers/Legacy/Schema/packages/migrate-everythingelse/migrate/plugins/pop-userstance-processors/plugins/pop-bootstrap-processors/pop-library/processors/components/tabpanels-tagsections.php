<?php

class UserStance_Module_Processor_TagSectionTabPanelComponents extends PoP_Module_Processor_TagSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_TAGSTANCES = 'tabpanel-tagstances';
    public final const COMPONENT_TABPANEL_TAGSTANCES_PRO = 'tabpanel-tagstances-pro';
    public final const COMPONENT_TABPANEL_TAGSTANCES_NEUTRAL = 'tabpanel-tagstances-neutral';
    public final const COMPONENT_TABPANEL_TAGSTANCES_AGAINST = 'tabpanel-tagstances-against';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_TAGSTANCES],
            [self::class, self::COMPONENT_TABPANEL_TAGSTANCES_PRO],
            [self::class, self::COMPONENT_TABPANEL_TAGSTANCES_NEUTRAL],
            [self::class, self::COMPONENT_TABPANEL_TAGSTANCES_AGAINST],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_TAGSTANCES:
            case self::COMPONENT_TABPANEL_TAGSTANCES_PRO:
            case self::COMPONENT_TABPANEL_TAGSTANCES_NEUTRAL:
            case self::COMPONENT_TABPANEL_TAGSTANCES_AGAINST:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_TAGSTANCES);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_TAGSTANCES:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_TAGSTANCES_PRO:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_TAGSTANCES_NEUTRAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_TAGSTANCES_AGAINST:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_TAGSTANCES:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_TAGSTANCES_PRO:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_PRO_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_TAGSTANCES_NEUTRAL:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_TAGSTANCES_AGAINST:
                $ret = array(
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-submodule' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_TAGSTANCES_AGAINST_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


