<?php

class LPPC_Module_Processor_SectionTabPanelComponents extends PoP_Module_Processor_SectionTabPanelComponentsBase
{
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS00 = 'tabpanel-mycategoryposts00';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS01 = 'tabpanel-mycategoryposts01';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS02 = 'tabpanel-mycategoryposts02';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS03 = 'tabpanel-mycategoryposts03';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS04 = 'tabpanel-mycategoryposts04';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS05 = 'tabpanel-mycategoryposts05';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS06 = 'tabpanel-mycategoryposts06';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS07 = 'tabpanel-mycategoryposts07';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS08 = 'tabpanel-mycategoryposts08';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS09 = 'tabpanel-mycategoryposts09';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS10 = 'tabpanel-mycategoryposts10';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS11 = 'tabpanel-mycategoryposts11';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS12 = 'tabpanel-mycategoryposts12';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS13 = 'tabpanel-mycategoryposts13';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS14 = 'tabpanel-mycategoryposts14';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS15 = 'tabpanel-mycategoryposts15';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS16 = 'tabpanel-mycategoryposts16';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS17 = 'tabpanel-mycategoryposts17';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS18 = 'tabpanel-mycategoryposts18';
    public final const MODULE_TABPANEL_MYCATEGORYPOSTS19 = 'tabpanel-mycategoryposts19';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS00],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS01],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS02],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS03],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS04],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS05],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS06],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS07],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS08],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS09],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS10],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS11],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS12],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS13],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS14],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS15],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS16],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS17],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS18],
            [self::class, self::MODULE_TABPANEL_MYCATEGORYPOSTS19],
        );
    }

    protected function getDefaultActivepanelFormat(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS00:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS01:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS02:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS03:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS04:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS05:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS06:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS07:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS08:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS09:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS10:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS11:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS12:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS13:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS14:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS15:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS16:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS17:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS18:
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS19:
                return PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);
        }

        return parent::getDefaultActivepanelFormat($component);
    }

    public function getPanelSubmodules(array $component)
    {
        $ret = parent::getPanelSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS00:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS00_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS00_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS00_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS01:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS01_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS01_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS01_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS02:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS02_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS02_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS02_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS03:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS03_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS03_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS03_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS04:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS04_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS04_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS04_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS05:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS05_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS05_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS05_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS06:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS06_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS06_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS06_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS07:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS07_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS07_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS07_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS08:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS08_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS08_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS08_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS09:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS09_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS09_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS09_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS10:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS10_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS10_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS10_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS11:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS11_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS11_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS11_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS12:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS12_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS12_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS12_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS13:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS13_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS13_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS13_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS14:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS14_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS14_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS14_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS15:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS15_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS15_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS15_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS16:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS16_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS16_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS16_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS17:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS17_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS17_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS17_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS18:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS18_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS18_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS18_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS19:
                $ret = array_merge(
                    $ret,
                    array(
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS19_TABLE_EDIT],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS19_SCROLL_SIMPLEVIEWPREVIEW],
                        [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS19_SCROLL_FULLVIEWPREVIEW],
                    )
                );
                break;
        }

        return $ret;
    }

    public function getPanelHeaders(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_TABPANEL_MYCATEGORYPOSTS00:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS00_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS00_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS00_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS00_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS01:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS01_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS01_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS01_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS01_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS02:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS02_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS02_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS02_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS02_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS03:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS03_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS03_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS03_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS03_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS04:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS04_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS04_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS04_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS04_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS05:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS05_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS05_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS05_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS05_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS06:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS06_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS06_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS06_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS06_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS07:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS07_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS07_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS07_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS07_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS08:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS08_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS08_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS08_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS08_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS09:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS09_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS09_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS09_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS09_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS10:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS10_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS10_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS10_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS10_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS11:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS11_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS11_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS11_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS11_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS12:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS12_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS12_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS12_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS12_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS13:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS13_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS13_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS13_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS13_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS14:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS14_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS14_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS14_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS14_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS15:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS15_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS15_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS15_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS15_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS16:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS16_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS16_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS16_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS16_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS17:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS17_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS17_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS17_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS17_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS18:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS18_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS18_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS18_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS18_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );

            case self::MODULE_TABPANEL_MYCATEGORYPOSTS19:
                return array(
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS19_TABLE_EDIT],
                    ],
                    [
                        'header-submodule' => [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS19_SCROLL_SIMPLEVIEWPREVIEW],
                        'subheader-submodules' =>  array(
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS19_SCROLL_SIMPLEVIEWPREVIEW],
                            [LPPC_Module_Processor_MySectionDataloads::class, LPPC_Module_Processor_MySectionDataloads::MODULE_DATALOAD_MYCATEGORYPOSTS19_SCROLL_FULLVIEWPREVIEW],
                        ),
                    ],
                );
        }

        return parent::getPanelHeaders($component, $props);
    }
}


