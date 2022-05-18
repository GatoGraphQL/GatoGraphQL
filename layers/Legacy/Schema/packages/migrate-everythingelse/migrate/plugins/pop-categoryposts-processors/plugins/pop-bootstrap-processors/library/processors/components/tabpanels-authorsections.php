<?php

class CPP_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS00 = 'tabpanel-authorcategoryposts00';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS01 = 'tabpanel-authorcategoryposts01';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS02 = 'tabpanel-authorcategoryposts02';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS03 = 'tabpanel-authorcategoryposts03';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS04 = 'tabpanel-authorcategoryposts04';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS05 = 'tabpanel-authorcategoryposts05';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS06 = 'tabpanel-authorcategoryposts06';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS07 = 'tabpanel-authorcategoryposts07';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS08 = 'tabpanel-authorcategoryposts08';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS09 = 'tabpanel-authorcategoryposts09';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS10 = 'tabpanel-authorcategoryposts10';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS11 = 'tabpanel-authorcategoryposts11';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS12 = 'tabpanel-authorcategoryposts12';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS13 = 'tabpanel-authorcategoryposts13';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS14 = 'tabpanel-authorcategoryposts14';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS15 = 'tabpanel-authorcategoryposts15';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS16 = 'tabpanel-authorcategoryposts16';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS17 = 'tabpanel-authorcategoryposts17';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS18 = 'tabpanel-authorcategoryposts18';
    public final const COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS19 = 'tabpanel-authorcategoryposts19';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS00],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS01],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS02],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS03],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS04],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS05],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS06],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS07],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS08],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS09],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS10],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS11],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS12],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS13],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS14],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS15],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS16],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS17],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS18],
            [self::class, self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS19],
        );
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS00:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS01:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS02:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS03:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS04:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS05:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS06:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS07:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS08:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS09:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS10:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS11:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS12:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS13:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS14:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS15:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS16:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS17:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS18:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORCATEGORYPOSTS19:
                $ret = array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


