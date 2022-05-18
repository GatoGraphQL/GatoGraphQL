<?php

class PoP_CategoryPosts_Module_Processor_ScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW = 'scrollinner-categoryposts00-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW = 'scrollinner-categoryposts01-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW = 'scrollinner-categoryposts02-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW = 'scrollinner-categoryposts03-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW = 'scrollinner-categoryposts04-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW = 'scrollinner-categoryposts05-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW = 'scrollinner-categoryposts06-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW = 'scrollinner-categoryposts07-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW = 'scrollinner-categoryposts08-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW = 'scrollinner-categoryposts09-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW = 'scrollinner-categoryposts10-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW = 'scrollinner-categoryposts11-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW = 'scrollinner-categoryposts12-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW = 'scrollinner-categoryposts13-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW = 'scrollinner-categoryposts14-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW = 'scrollinner-categoryposts15-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW = 'scrollinner-categoryposts16-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW = 'scrollinner-categoryposts17-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW = 'scrollinner-categoryposts18-simpleview';
    public final const MODULE_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW = 'scrollinner-categoryposts19-simpleview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $categories = array(
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS00,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS01,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS02,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS03,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS04,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS05,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS06,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS07,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS08,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS09,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS10,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS11,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS12,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS13,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS14,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS15,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS16,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS17,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS18,
            self::COMPONENT_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW => POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS19,
        );

        // Allow PoP Post Category Layouts to add a more specific layout for this category
        if ($layout = \PoP\Root\App::applyFilters(
            'PoP_CategoryPosts_Module_Processor_ScrollInners:layout',
            [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW],
            $categories[$component[1]]
        )) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


