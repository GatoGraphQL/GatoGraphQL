<?php

class PoP_NoSearchCategoryPosts_Module_Processor_Scrolls extends PoP_Module_Processor_ScrollsBase
{
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS00_SIMPLEVIEW = 'scroll-nosearchcategoryposts00-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS01_SIMPLEVIEW = 'scroll-nosearchcategoryposts01-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS02_SIMPLEVIEW = 'scroll-nosearchcategoryposts02-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS03_SIMPLEVIEW = 'scroll-nosearchcategoryposts03-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS04_SIMPLEVIEW = 'scroll-nosearchcategoryposts04-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS05_SIMPLEVIEW = 'scroll-nosearchcategoryposts05-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS06_SIMPLEVIEW = 'scroll-nosearchcategoryposts06-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS07_SIMPLEVIEW = 'scroll-nosearchcategoryposts07-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS08_SIMPLEVIEW = 'scroll-nosearchcategoryposts08-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS09_SIMPLEVIEW = 'scroll-nosearchcategoryposts09-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS10_SIMPLEVIEW = 'scroll-nosearchcategoryposts10-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS11_SIMPLEVIEW = 'scroll-nosearchcategoryposts11-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS12_SIMPLEVIEW = 'scroll-nosearchcategoryposts12-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS13_SIMPLEVIEW = 'scroll-nosearchcategoryposts13-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS14_SIMPLEVIEW = 'scroll-nosearchcategoryposts14-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS15_SIMPLEVIEW = 'scroll-nosearchcategoryposts15-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS16_SIMPLEVIEW = 'scroll-nosearchcategoryposts16-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS17_SIMPLEVIEW = 'scroll-nosearchcategoryposts17-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS18_SIMPLEVIEW = 'scroll-nosearchcategoryposts18-simpleview';
    public const MODULE_SCROLL_NOSEARCHCATEGORYPOSTS19_SIMPLEVIEW = 'scroll-nosearchcategoryposts19-simpleview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS00_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS01_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS02_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS03_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS04_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS05_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS06_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS07_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS08_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS09_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS10_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS11_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS12_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS13_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS14_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS15_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS16_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS17_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS18_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS19_SIMPLEVIEW],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS00_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS00_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS01_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS01_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS02_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS02_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS03_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS03_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS04_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS04_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS05_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS05_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS06_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS06_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS07_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS07_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS08_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS08_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS09_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS09_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS10_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS10_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS11_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS11_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS12_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS12_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS13_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS13_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS14_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS14_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS15_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS15_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS16_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS16_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS17_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS17_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS18_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS18_SIMPLEVIEW],
            self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS19_SIMPLEVIEW => [PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::class, PoP_NoSearchCategoryPosts_Module_Processor_ScrollInners::MODULE_SCROLLINNER_NOSEARCHCATEGORYPOSTS19_SIMPLEVIEW],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Extra classes
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS00_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS01_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS02_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS03_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS04_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS05_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS06_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS07_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS08_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS09_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS10_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS11_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS12_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS13_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS14_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS15_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS16_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS17_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS18_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_NOSEARCHCATEGORYPOSTS19_SIMPLEVIEW],
        );

        $extra_class = '';
        if (in_array($module, $simpleviews)) {
            $extra_class = 'simpleview';
        }
        $this->appendProp($module, $props, 'class', $extra_class);

        parent::initModelProps($module, $props);
    }
}


