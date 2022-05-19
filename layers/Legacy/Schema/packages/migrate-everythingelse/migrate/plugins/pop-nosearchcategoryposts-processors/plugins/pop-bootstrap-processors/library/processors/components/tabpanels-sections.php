<?php

class NSCPP_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS00 = 'tabpanel-nosearchcategoryposts00';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS01 = 'tabpanel-nosearchcategoryposts01';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS02 = 'tabpanel-nosearchcategoryposts02';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS03 = 'tabpanel-nosearchcategoryposts03';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS04 = 'tabpanel-nosearchcategoryposts04';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS05 = 'tabpanel-nosearchcategoryposts05';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS06 = 'tabpanel-nosearchcategoryposts06';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS07 = 'tabpanel-nosearchcategoryposts07';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS08 = 'tabpanel-nosearchcategoryposts08';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS09 = 'tabpanel-nosearchcategoryposts09';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS10 = 'tabpanel-nosearchcategoryposts10';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS11 = 'tabpanel-nosearchcategoryposts11';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS12 = 'tabpanel-nosearchcategoryposts12';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS13 = 'tabpanel-nosearchcategoryposts13';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS14 = 'tabpanel-nosearchcategoryposts14';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS15 = 'tabpanel-nosearchcategoryposts15';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS16 = 'tabpanel-nosearchcategoryposts16';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS17 = 'tabpanel-nosearchcategoryposts17';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS18 = 'tabpanel-nosearchcategoryposts18';
    public final const COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS19 = 'tabpanel-nosearchcategoryposts19';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS00],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS01],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS02],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS03],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS04],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS05],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS06],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS07],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS08],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS09],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS10],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS11],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS12],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS13],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS14],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS15],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS16],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS17],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS18],
            [self::class, self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS19],
        );
    }

    public function getPanelSubcomponents(array $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS00:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS01:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS02:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS03:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS04:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS05:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS06:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS07:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS08:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS09:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS10:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS11:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS12:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS13:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS14:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS15:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS16:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS17:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS18:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_NOSEARCHCATEGORYPOSTS19:
                return array(
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_NOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                        ),
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}


