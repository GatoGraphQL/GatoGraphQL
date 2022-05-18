<?php

class CPP_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_CATEGORYPOSTS00 = 'categoryposts00-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS01 = 'categoryposts01-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS02 = 'categoryposts02-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS03 = 'categoryposts03-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS04 = 'categoryposts04-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS05 = 'categoryposts05-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS06 = 'categoryposts06-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS07 = 'categoryposts07-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS08 = 'categoryposts08-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS09 = 'categoryposts09-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS10 = 'categoryposts10-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS11 = 'categoryposts11-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS12 = 'categoryposts12-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS13 = 'categoryposts13-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS14 = 'categoryposts14-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS15 = 'categoryposts15-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS16 = 'categoryposts16-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS17 = 'categoryposts17-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS18 = 'categoryposts18-tabpanel';
    public final const MODULE_TABPANEL_CATEGORYPOSTS19 = 'categoryposts19-tabpanel';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS00],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS01],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS02],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS03],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS04],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS05],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS06],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS07],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS08],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS09],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS10],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS11],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS12],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS13],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS14],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS15],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS16],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS17],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS18],
            [self::class, self::MODULE_TABPANEL_CATEGORYPOSTS19],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_TABPANEL_CATEGORYPOSTS00 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_TABPANEL_CATEGORYPOSTS01 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_TABPANEL_CATEGORYPOSTS02 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_TABPANEL_CATEGORYPOSTS03 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_TABPANEL_CATEGORYPOSTS04 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_TABPANEL_CATEGORYPOSTS05 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_TABPANEL_CATEGORYPOSTS06 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_TABPANEL_CATEGORYPOSTS07 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_TABPANEL_CATEGORYPOSTS08 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_TABPANEL_CATEGORYPOSTS09 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_TABPANEL_CATEGORYPOSTS10 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_TABPANEL_CATEGORYPOSTS11 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_TABPANEL_CATEGORYPOSTS12 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_TABPANEL_CATEGORYPOSTS13 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_TABPANEL_CATEGORYPOSTS14 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_TABPANEL_CATEGORYPOSTS15 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_TABPANEL_CATEGORYPOSTS16 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_TABPANEL_CATEGORYPOSTS17 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_TABPANEL_CATEGORYPOSTS18 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_TABPANEL_CATEGORYPOSTS19 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_TABPANEL_CATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
                    )
                );
                break;

            case self::MODULE_TABPANEL_CATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_TABPANEL_CATEGORYPOSTS00:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS01:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS02:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS03:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS04:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS05:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS06:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS07:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS08:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS09:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS10:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS11:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS12:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS13:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS14:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS15:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS16:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS17:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS18:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_CATEGORYPOSTS19:
                return array(
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-submodule' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],
                        'subheader-submodules' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],
                        ),
                    ],
                );
        }

        return parent::getPanelHeaders($module, $props);
    }
}


