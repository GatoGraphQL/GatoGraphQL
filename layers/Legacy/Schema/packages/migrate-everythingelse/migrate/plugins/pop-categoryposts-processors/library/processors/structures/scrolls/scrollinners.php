<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_CategoryPosts_Module_Processor_ScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW = 'scrollinner-categoryposts00-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW = 'scrollinner-categoryposts01-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW = 'scrollinner-categoryposts02-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW = 'scrollinner-categoryposts03-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW = 'scrollinner-categoryposts04-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW = 'scrollinner-categoryposts05-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW = 'scrollinner-categoryposts06-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW = 'scrollinner-categoryposts07-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW = 'scrollinner-categoryposts08-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW = 'scrollinner-categoryposts09-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW = 'scrollinner-categoryposts10-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW = 'scrollinner-categoryposts11-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW = 'scrollinner-categoryposts12-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW = 'scrollinner-categoryposts13-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW = 'scrollinner-categoryposts14-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW = 'scrollinner-categoryposts15-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW = 'scrollinner-categoryposts16-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW = 'scrollinner-categoryposts17-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW = 'scrollinner-categoryposts18-simpleview';
    public const MODULE_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW = 'scrollinner-categoryposts19-simpleview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $categories = array(
            self::MODULE_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS00,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS01,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS02,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS03,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS04,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS05,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS06,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS07,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS08,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS09,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS10,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS11,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS12,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS13,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS14,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS15,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS16,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS17,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS18,
            self::MODULE_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS19,
        );

        // Allow PoP Post Category Layouts to add a more specific layout for this category
        if ($layout = \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_CategoryPosts_Module_Processor_ScrollInners:layout',
            [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW],
            $categories[$module[1]]
        )) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


