<?php

class CPP_Module_Processor_TagSectionTabPanelComponents extends PoP_Module_Processor_TagSectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS00 = 'tabpanel-tagcategoryposts00';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS01 = 'tabpanel-tagcategoryposts01';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS02 = 'tabpanel-tagcategoryposts02';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS03 = 'tabpanel-tagcategoryposts03';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS04 = 'tabpanel-tagcategoryposts04';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS05 = 'tabpanel-tagcategoryposts05';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS06 = 'tabpanel-tagcategoryposts06';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS07 = 'tabpanel-tagcategoryposts07';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS08 = 'tabpanel-tagcategoryposts08';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS09 = 'tabpanel-tagcategoryposts09';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS10 = 'tabpanel-tagcategoryposts10';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS11 = 'tabpanel-tagcategoryposts11';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS12 = 'tabpanel-tagcategoryposts12';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS13 = 'tabpanel-tagcategoryposts13';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS14 = 'tabpanel-tagcategoryposts14';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS15 = 'tabpanel-tagcategoryposts15';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS16 = 'tabpanel-tagcategoryposts16';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS17 = 'tabpanel-tagcategoryposts17';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS18 = 'tabpanel-tagcategoryposts18';
    public final const MODULE_TABPANEL_TAGCATEGORYPOSTS19 = 'tabpanel-tagcategoryposts19';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS00],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS01],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS02],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS03],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS04],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS05],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS06],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS07],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS08],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS09],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS10],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS11],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS12],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS13],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS14],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS15],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS16],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS17],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS18],
            [self::class, self::MODULE_TABPANEL_TAGCATEGORYPOSTS19],
        );
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS00:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS01:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS02:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS03:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS04:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS05:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS06:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS07:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS08:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS09:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS10:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS11:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS12:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS13:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS14:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS15:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS16:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS17:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS18:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST],
                        ),
                    ],
                );
                break;

            case self::MODULE_TABPANEL_TAGCATEGORYPOSTS19:
                $ret = array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST],
                        ),
                    ],
                );
                break;
        }

        return $ret ?? parent::getPanelHeaders($module, $props);
    }
}


