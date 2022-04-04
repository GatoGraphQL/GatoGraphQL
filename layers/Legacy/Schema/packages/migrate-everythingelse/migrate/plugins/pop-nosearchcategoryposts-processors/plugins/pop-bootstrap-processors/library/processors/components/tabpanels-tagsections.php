<?php

class NSCPP_Module_Processor_TagSectionTabPanelComponents extends PoP_Module_Processor_TagSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00 = 'tabpanel-tagnosearchcategoryposts00';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01 = 'tabpanel-tagnosearchcategoryposts01';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02 = 'tabpanel-tagnosearchcategoryposts02';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03 = 'tabpanel-tagnosearchcategoryposts03';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04 = 'tabpanel-tagnosearchcategoryposts04';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05 = 'tabpanel-tagnosearchcategoryposts05';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06 = 'tabpanel-tagnosearchcategoryposts06';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07 = 'tabpanel-tagnosearchcategoryposts07';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08 = 'tabpanel-tagnosearchcategoryposts08';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09 = 'tabpanel-tagnosearchcategoryposts09';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10 = 'tabpanel-tagnosearchcategoryposts10';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11 = 'tabpanel-tagnosearchcategoryposts11';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12 = 'tabpanel-tagnosearchcategoryposts12';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13 = 'tabpanel-tagnosearchcategoryposts13';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14 = 'tabpanel-tagnosearchcategoryposts14';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15 = 'tabpanel-tagnosearchcategoryposts15';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16 = 'tabpanel-tagnosearchcategoryposts16';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17 = 'tabpanel-tagnosearchcategoryposts17';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18 = 'tabpanel-tagnosearchcategoryposts18';
    public final const MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19 = 'tabpanel-tagnosearchcategoryposts19';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18],
            [self::class, self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19],
        );
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                        [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS00:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS00_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS01:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS01_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS02:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS02_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS03:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS03_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS04:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS04_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS05:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS05_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS06:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS06_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS07:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS07_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS08:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS08_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS09:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS09_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS10:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS10_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS11:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS11_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS12:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS12_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS13:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS13_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS14:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS14_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS15:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS15_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS16:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS16_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS17:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS17_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS18:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS18_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGNOSEARCHCATEGORYPOSTS19:
                $ret = array(
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_DETAILS],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                            [NSCPP_Module_Processor_SectionDataloads::class, NSCPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGNOSEARCHCATEGORYPOSTS19_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($module, $props);
    }
}


