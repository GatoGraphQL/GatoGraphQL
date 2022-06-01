<?php

class PoPTheme_Wassup_AE_Module_Processor_Scrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS = 'scroll-automatedemails-latestcontent-details';
    public final const COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW = 'scroll-automatedemails-latestcontent-simpleview';
    public final const COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW = 'scroll-automatedemails-latestcontent-fullview';
    public final const COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL = 'scroll-automatedemails-latestcontent-thumbnail';
    public final const COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST = 'scroll-automatedemails-latestcontent-list';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS,
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW,
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW,
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL,
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST,
        );
    }


    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS],
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW],
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW],
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL],
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST => [PoPTheme_Wassup_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_AE_Module_Processor_ScrollInners::COMPONENT_SCROLLINNER_AUTOMATEDEMAILS_LATESTCONTENT_LIST],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_THUMBNAIL,
        );
        $lists = array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_LIST,
        );
        $details = array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_DETAILS,
        );
        $simpleviews = array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_SIMPLEVIEW,
        );
        $fullviews = array(
            self::COMPONENT_SCROLL_AUTOMATEDEMAILS_LATESTCONTENT_FULLVIEW,
        );

        $extra_class = '';
        if (in_array($component, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($component, $details)) {
            $extra_class = 'details';
        } elseif (in_array($component, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($component, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        parent::initModelProps($component, $props);
    }
}


