<?php

class PoP_ContentPostLinks_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_LINKS_NAVIGATOR = 'scroll-links-navigator';
    public final const MODULE_SCROLL_LINKS_ADDONS = 'scroll-links-addons';
    public final const MODULE_SCROLL_LINKS_DETAILS = 'scroll-links-details';
    public final const MODULE_SCROLL_LINKS_SIMPLEVIEW = 'scroll-links-simpleview';
    public final const MODULE_SCROLL_LINKS_FULLVIEW = 'scroll-links-fullview';
    public final const MODULE_SCROLL_AUTHORLINKS_FULLVIEW = 'scroll-authorlinks-fullview';
    public final const MODULE_SCROLL_LINKS_THUMBNAIL = 'scroll-links-thumbnail';
    public final const MODULE_SCROLL_LINKS_LIST = 'scroll-links-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_LINKS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLL_LINKS_ADDONS],
            [self::class, self::COMPONENT_SCROLL_LINKS_DETAILS],
            [self::class, self::COMPONENT_SCROLL_LINKS_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLL_LINKS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLL_LINKS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLL_LINKS_LIST],
            [self::class, self::COMPONENT_SCROLL_AUTHORLINKS_FULLVIEW],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_LINKS_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LINKS_NAVIGATOR],
            self::COMPONENT_SCROLL_LINKS_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LINKS_ADDONS],
            self::COMPONENT_SCROLL_LINKS_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LINKS_DETAILS],
            self::COMPONENT_SCROLL_LINKS_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LINKS_SIMPLEVIEW],
            self::COMPONENT_SCROLL_LINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LINKS_FULLVIEW],
            self::COMPONENT_SCROLL_LINKS_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LINKS_THUMBNAIL],
            self::COMPONENT_SCROLL_LINKS_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LINKS_LIST],
            self::COMPONENT_SCROLL_AUTHORLINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_AUTHORLINKS_FULLVIEW],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::COMPONENT_SCROLL_LINKS_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::COMPONENT_SCROLL_LINKS_LIST],
        );
        $details = array(
            [self::class, self::COMPONENT_SCROLL_LINKS_DETAILS],
        );
        $navigators = array(
            [self::class, self::COMPONENT_SCROLL_LINKS_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::COMPONENT_SCROLL_LINKS_ADDONS],
        );
        $simpleviews = array(
            [self::class, self::COMPONENT_SCROLL_LINKS_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_SCROLL_LINKS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLL_AUTHORLINKS_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($component, $navigators)) {
            $extra_class = 'navigator text-inverse';
        } elseif (in_array($component, $addons)) {
            $extra_class = 'addons';
        } elseif (in_array($component, $simpleviews)) {
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


        $inner = $this->getInnerSubmodule($component);
        if (in_array($component, $navigators)) {
            // Make it activeItem: highlight on viewing the corresponding fullview
            $this->appendProp($inner, $props, 'class', 'pop-activeitem');
        }

        parent::initModelProps($component, $props);
    }
}


