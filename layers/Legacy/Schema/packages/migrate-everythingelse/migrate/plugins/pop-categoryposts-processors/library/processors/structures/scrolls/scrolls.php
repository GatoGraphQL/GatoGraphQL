<?php

class PoP_CategoryPosts_Module_Processor_Scrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_CATEGORYPOSTS00_SIMPLEVIEW = 'scroll-categoryposts00-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS01_SIMPLEVIEW = 'scroll-categoryposts01-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS02_SIMPLEVIEW = 'scroll-categoryposts02-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS03_SIMPLEVIEW = 'scroll-categoryposts03-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS04_SIMPLEVIEW = 'scroll-categoryposts04-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS05_SIMPLEVIEW = 'scroll-categoryposts05-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS06_SIMPLEVIEW = 'scroll-categoryposts06-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS07_SIMPLEVIEW = 'scroll-categoryposts07-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS08_SIMPLEVIEW = 'scroll-categoryposts08-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS09_SIMPLEVIEW = 'scroll-categoryposts09-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS10_SIMPLEVIEW = 'scroll-categoryposts10-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS11_SIMPLEVIEW = 'scroll-categoryposts11-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS12_SIMPLEVIEW = 'scroll-categoryposts12-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS13_SIMPLEVIEW = 'scroll-categoryposts13-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS14_SIMPLEVIEW = 'scroll-categoryposts14-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS15_SIMPLEVIEW = 'scroll-categoryposts15-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS16_SIMPLEVIEW = 'scroll-categoryposts16-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS17_SIMPLEVIEW = 'scroll-categoryposts17-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS18_SIMPLEVIEW = 'scroll-categoryposts18-simpleview';
    public final const MODULE_SCROLL_CATEGORYPOSTS19_SIMPLEVIEW = 'scroll-categoryposts19-simpleview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS00_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS01_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS02_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS03_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS04_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS05_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS06_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS07_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS08_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS09_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS10_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS11_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS12_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS13_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS14_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS15_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS16_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS17_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS18_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS19_SIMPLEVIEW],
        );
    }


    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_SCROLL_CATEGORYPOSTS00_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS00_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS01_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS01_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS02_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS02_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS03_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS03_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS04_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS04_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS05_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS05_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS06_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS06_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS07_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS07_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS08_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS08_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS09_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS09_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS10_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS10_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS11_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS11_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS12_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS12_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS13_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS13_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS14_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS14_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS15_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS15_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS16_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS16_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS17_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS17_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS18_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS18_SIMPLEVIEW],
            self::MODULE_SCROLL_CATEGORYPOSTS19_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_ScrollInners::class, PoP_CategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_CATEGORYPOSTS19_SIMPLEVIEW],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Extra classes
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS00_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS01_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS02_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS03_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS04_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS05_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS06_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS07_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS08_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS09_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS10_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS11_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS12_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS13_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS14_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS15_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS16_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS17_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS18_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CATEGORYPOSTS19_SIMPLEVIEW],
        );

        $extra_class = '';
        if (in_array($componentVariation, $simpleviews)) {
            $extra_class = 'simpleview';
        }
        $this->appendProp($componentVariation, $props, 'class', $extra_class);

        parent::initModelProps($componentVariation, $props);
    }
}


