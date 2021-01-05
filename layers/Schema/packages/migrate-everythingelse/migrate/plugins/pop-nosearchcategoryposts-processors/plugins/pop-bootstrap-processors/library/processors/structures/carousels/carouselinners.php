<?php

class NSCPP_Module_Processor_CarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00 = 'carouselinner-nosearchcategoryposts00';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01 = 'carouselinner-nosearchcategoryposts01';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02 = 'carouselinner-nosearchcategoryposts02';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03 = 'carouselinner-nosearchcategoryposts03';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04 = 'carouselinner-nosearchcategoryposts04';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05 = 'carouselinner-nosearchcategoryposts05';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06 = 'carouselinner-nosearchcategoryposts06';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07 = 'carouselinner-nosearchcategoryposts07';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08 = 'carouselinner-nosearchcategoryposts08';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09 = 'carouselinner-nosearchcategoryposts09';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10 = 'carouselinner-nosearchcategoryposts10';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11 = 'carouselinner-nosearchcategoryposts11';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12 = 'carouselinner-nosearchcategoryposts12';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13 = 'carouselinner-nosearchcategoryposts13';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14 = 'carouselinner-nosearchcategoryposts14';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15 = 'carouselinner-nosearchcategoryposts15';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16 = 'carouselinner-nosearchcategoryposts16';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17 = 'carouselinner-nosearchcategoryposts17';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18 = 'carouselinner-nosearchcategoryposts18';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19 = 'carouselinner-nosearchcategoryposts19';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00_CONTENT = 'carouselinner-nosearchcategoryposts00-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01_CONTENT = 'carouselinner-nosearchcategoryposts01-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02_CONTENT = 'carouselinner-nosearchcategoryposts02-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03_CONTENT = 'carouselinner-nosearchcategoryposts03-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04_CONTENT = 'carouselinner-nosearchcategoryposts04-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05_CONTENT = 'carouselinner-nosearchcategoryposts05-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06_CONTENT = 'carouselinner-nosearchcategoryposts06-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07_CONTENT = 'carouselinner-nosearchcategoryposts07-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08_CONTENT = 'carouselinner-nosearchcategoryposts08-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09_CONTENT = 'carouselinner-nosearchcategoryposts09-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10_CONTENT = 'carouselinner-nosearchcategoryposts10-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11_CONTENT = 'carouselinner-nosearchcategoryposts11-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12_CONTENT = 'carouselinner-nosearchcategoryposts12-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13_CONTENT = 'carouselinner-nosearchcategoryposts13-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14_CONTENT = 'carouselinner-nosearchcategoryposts14-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15_CONTENT = 'carouselinner-nosearchcategoryposts15-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16_CONTENT = 'carouselinner-nosearchcategoryposts16-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17_CONTENT = 'carouselinner-nosearchcategoryposts17-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18_CONTENT = 'carouselinner-nosearchcategoryposts18-content';
    public const MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19_CONTENT = 'carouselinner-nosearchcategoryposts19-content';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18_CONTENT],
            [self::class, self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19_CONTENT],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19:
                // if ($grid = $this->getProp($module, $props, 'layout-grid')) {
                //     return $grid;
                // }

                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12',
                    'divider' => 3,
                );

            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18_CONTENT:
            case self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19_CONTENT:
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
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19 => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS00_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS01_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS02_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS03_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS04_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS05_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS06_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS07_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS08_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS09_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS10_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS11_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS12_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS13_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS14_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS15_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS16_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS17_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS18_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
            self::MODULE_CAROUSELINNER_NOSEARCHCATEGORYPOSTS19_CONTENT => [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POST],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] =$layout;
        }

        return $ret;
    }
}


