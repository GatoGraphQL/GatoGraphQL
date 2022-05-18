<?php

class UserStance_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_AUTHORSTANCES = 'tabpanel-authorstances';
    public final const COMPONENT_TABPANEL_AUTHORSTANCES_PRO = 'tabpanel-authorstances-pro';
    public final const COMPONENT_TABPANEL_AUTHORSTANCES_NEUTRAL = 'tabpanel-authorstances-neutral';
    public final const COMPONENT_TABPANEL_AUTHORSTANCES_AGAINST = 'tabpanel-authorstances-against';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_AUTHORSTANCES],
            [self::class, self::COMPONENT_TABPANEL_AUTHORSTANCES_PRO],
            [self::class, self::COMPONENT_TABPANEL_AUTHORSTANCES_NEUTRAL],
            [self::class, self::COMPONENT_TABPANEL_AUTHORSTANCES_AGAINST],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORSTANCES:
            case self::COMPONENT_TABPANEL_AUTHORSTANCES_PRO:
            case self::COMPONENT_TABPANEL_AUTHORSTANCES_NEUTRAL:
            case self::COMPONENT_TABPANEL_AUTHORSTANCES_AGAINST:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_USERSTANCE_SCREEN_AUTHORSTANCES);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORSTANCES:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSTANCES_PRO:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSTANCES_NEUTRAL:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSTANCES_AGAINST:
                $ret = array_merge(
                    $ret,
                    array(
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
                        [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORSTANCES:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSTANCES_PRO:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_PRO_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSTANCES_NEUTRAL:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_NEUTRAL_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORSTANCES_AGAINST:
                $ret = array(
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_FULLVIEW],
                    ],
                    [
                        'header-subcomponent' => [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_LIST],
                            [UserStance_Module_Processor_CustomSectionDataloads::class, UserStance_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORSTANCES_AGAINST_SCROLL_THUMBNAIL],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


