<?php

class CPP_Module_Processor_CarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS00 = 'carouselinner-categoryposts00';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS01 = 'carouselinner-categoryposts01';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS02 = 'carouselinner-categoryposts02';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS03 = 'carouselinner-categoryposts03';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS04 = 'carouselinner-categoryposts04';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS05 = 'carouselinner-categoryposts05';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS06 = 'carouselinner-categoryposts06';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS07 = 'carouselinner-categoryposts07';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS08 = 'carouselinner-categoryposts08';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS09 = 'carouselinner-categoryposts09';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS10 = 'carouselinner-categoryposts10';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS11 = 'carouselinner-categoryposts11';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS12 = 'carouselinner-categoryposts12';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS13 = 'carouselinner-categoryposts13';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS14 = 'carouselinner-categoryposts14';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS15 = 'carouselinner-categoryposts15';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS16 = 'carouselinner-categoryposts16';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS17 = 'carouselinner-categoryposts17';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS18 = 'carouselinner-categoryposts18';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS19 = 'carouselinner-categoryposts19';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS00_CONTENT = 'carouselinner-categoryposts00-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS01_CONTENT = 'carouselinner-categoryposts01-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS02_CONTENT = 'carouselinner-categoryposts02-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS03_CONTENT = 'carouselinner-categoryposts03-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS04_CONTENT = 'carouselinner-categoryposts04-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS05_CONTENT = 'carouselinner-categoryposts05-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS06_CONTENT = 'carouselinner-categoryposts06-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS07_CONTENT = 'carouselinner-categoryposts07-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS08_CONTENT = 'carouselinner-categoryposts08-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS09_CONTENT = 'carouselinner-categoryposts09-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS10_CONTENT = 'carouselinner-categoryposts10-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS11_CONTENT = 'carouselinner-categoryposts11-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS12_CONTENT = 'carouselinner-categoryposts12-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS13_CONTENT = 'carouselinner-categoryposts13-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS14_CONTENT = 'carouselinner-categoryposts14-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS15_CONTENT = 'carouselinner-categoryposts15-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS16_CONTENT = 'carouselinner-categoryposts16-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS17_CONTENT = 'carouselinner-categoryposts17-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS18_CONTENT = 'carouselinner-categoryposts18-content';
    public const MODULE_CAROUSELINNER_CATEGORYPOSTS19_CONTENT = 'carouselinner-categoryposts19-content';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS00],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS01],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS02],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS03],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS04],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS05],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS06],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS07],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS08],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS09],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS10],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS11],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS12],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS13],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS14],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS15],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS16],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS17],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS18],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS19],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS00_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS01_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS02_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS03_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS04_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS05_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS06_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS07_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS08_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS09_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS10_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS11_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS12_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS13_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS14_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS15_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS16_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS17_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS18_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_CATEGORYPOSTS19_CONTENT],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS00:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS01:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS02:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS03:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS04:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS05:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS06:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS07:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS08:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS09:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS10:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS11:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS12:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS13:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS14:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS15:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS16:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS17:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS18:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS19:
                // if ($grid = $this->getProp($module, $props, 'layout-grid')) {
                //     return $grid;
                // }

                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12',
                    'divider' => 3,
                );

            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS00_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS01_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS02_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS03_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS04_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS05_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS06_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS07_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS08_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS09_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS10_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS11_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS12_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS13_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS14_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS15_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS16_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS17_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS18_CONTENT:
            case self::MODULE_CAROUSELINNER_CATEGORYPOSTS19_CONTENT:
                // if ($grid = $this->getProp($module, $props, 'layout-grid')) {
                //     return $grid;
                // }

                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12',
                    'divider' => 1,
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS00 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS01 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS02 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS03 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS04 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS05 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS06 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS07 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS08 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS09 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS10 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS11 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS12 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS13 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS14 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS15 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS16 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS17 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS18 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS19 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS00_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS01_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS02_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS03_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS04_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS05_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS06_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS07_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS08_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS09_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS10_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS11_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS12_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS13_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS14_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS15_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS16_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS17_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS18_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_CATEGORYPOSTS19_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
        );
        if ($layout = $layouts[$module[1]]) {
            $ret[] =$layout;
        }

        return $ret;
    }
}


