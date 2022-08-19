<?php

class CPP_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS00 = 'categoryposts00-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS01 = 'categoryposts01-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS02 = 'categoryposts02-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS03 = 'categoryposts03-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS04 = 'categoryposts04-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS05 = 'categoryposts05-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS06 = 'categoryposts06-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS07 = 'categoryposts07-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS08 = 'categoryposts08-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS09 = 'categoryposts09-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS10 = 'categoryposts10-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS11 = 'categoryposts11-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS12 = 'categoryposts12-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS13 = 'categoryposts13-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS14 = 'categoryposts14-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS15 = 'categoryposts15-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS16 = 'categoryposts16-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS17 = 'categoryposts17-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS18 = 'categoryposts18-tabpanel';
    public final const COMPONENT_TABPANEL_CATEGORYPOSTS19 = 'categoryposts19-tabpanel';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABPANEL_CATEGORYPOSTS00,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS01,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS02,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS03,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS04,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS05,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS06,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS07,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS08,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS09,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS10,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS11,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS12,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS13,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS14,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS15,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS16,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS17,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS18,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS19,
        );
    }

    public function getRelevantRoute(\PoP\ComponentModel\Component\Component $component, array &$props): ?string
    {
        return match($component->name) {
            self::COMPONENT_TABPANEL_CATEGORYPOSTS00 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS01 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS02 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS03 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS04 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS05 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS06 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS07 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS08 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS09 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS10 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS11 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS12 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS13 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS14 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS15 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS16 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS17 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS18 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::COMPONENT_TABPANEL_CATEGORYPOSTS19 => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getPanelSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_TABPANEL_CATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
                    )
                );
                break;

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL],
                        [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_TABPANEL_CATEGORYPOSTS00:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS01:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS02:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS03:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS04:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS05:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS06:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS07:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS08:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS09:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS10:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS11:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS12:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS13:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS14:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS15:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS16:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS17:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS18:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
                        ),
                    ],
                );

            case self::COMPONENT_TABPANEL_CATEGORYPOSTS19:
                return array(
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW],
                        ),
                    ],
                    [
                        'header-subcomponent' => [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],
                        'subheader-subcomponents' =>  array(
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL],
                            [CPP_Module_Processor_SectionDataloads::class, CPP_Module_Processor_SectionDataloads::COMPONENT_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],
                        ),
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}


