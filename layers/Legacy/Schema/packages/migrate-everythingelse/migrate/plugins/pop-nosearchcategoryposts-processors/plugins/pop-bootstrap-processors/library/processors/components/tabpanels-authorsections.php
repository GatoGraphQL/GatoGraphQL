<?php

class NSCPP_Module_Processor_AuthorSectionTabPanelComponents extends PoP_Module_Processor_AuthorSectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00 = 'tabpanel-authornosearchcategoryposts00';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01 = 'tabpanel-authornosearchcategoryposts01';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02 = 'tabpanel-authornosearchcategoryposts02';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03 = 'tabpanel-authornosearchcategoryposts03';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04 = 'tabpanel-authornosearchcategoryposts04';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05 = 'tabpanel-authornosearchcategoryposts05';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06 = 'tabpanel-authornosearchcategoryposts06';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07 = 'tabpanel-authornosearchcategoryposts07';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08 = 'tabpanel-authornosearchcategoryposts08';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09 = 'tabpanel-authornosearchcategoryposts09';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10 = 'tabpanel-authornosearchcategoryposts10';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11 = 'tabpanel-authornosearchcategoryposts11';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12 = 'tabpanel-authornosearchcategoryposts12';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13 = 'tabpanel-authornosearchcategoryposts13';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14 = 'tabpanel-authornosearchcategoryposts14';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15 = 'tabpanel-authornosearchcategoryposts15';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16 = 'tabpanel-authornosearchcategoryposts16';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17 = 'tabpanel-authornosearchcategoryposts17';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18 = 'tabpanel-authornosearchcategoryposts18';
    public final const COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19 = 'tabpanel-authornosearchcategoryposts19';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18],
            [self::class, self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19],
        );
    }

    public function getPanelSubcomponents(array $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS00:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS01:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS02:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS03:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS04:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS05:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS06:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS07:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS08:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS09:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS10:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS11:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS12:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS13:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS14:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS15:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS16:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS17:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS18:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::COMPONENT_TABPANEL_AUTHORNOSEARCHCATEGORYPOSTS19:
                $ret = array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_AUTHORNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($component, $props);
    }
}


