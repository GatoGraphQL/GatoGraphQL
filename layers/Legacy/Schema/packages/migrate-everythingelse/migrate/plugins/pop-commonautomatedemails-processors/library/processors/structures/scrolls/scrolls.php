<?php

class PoPTheme_Wassup_AE_Module_Processor_Scrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS = 'scroll-automatedemails-latestcontent-details';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW = 'scroll-automatedemails-latestcontent-simpleview';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW = 'scroll-automatedemails-latestcontent-fullview';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL = 'scroll-automatedemails-latestcontent-thumbnail';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST = 'scroll-automatedemails-latestcontent-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($module, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($module, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($module, $details)) {
            $extra_class = 'details';
        } elseif (in_array($module, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($module, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($module, $props, 'class', $extra_class);

        parent::initModelProps($module, $props);
    }
}


