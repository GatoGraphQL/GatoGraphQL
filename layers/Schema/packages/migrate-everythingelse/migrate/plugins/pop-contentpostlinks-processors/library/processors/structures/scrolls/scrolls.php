<?php

class PoP_ContentPostLinks_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public const MODULE_SCROLL_LINKS_NAVIGATOR = 'scroll-links-navigator';
    public const MODULE_SCROLL_LINKS_ADDONS = 'scroll-links-addons';
    public const MODULE_SCROLL_LINKS_DETAILS = 'scroll-links-details';
    public const MODULE_SCROLL_LINKS_SIMPLEVIEW = 'scroll-links-simpleview';
    public const MODULE_SCROLL_LINKS_FULLVIEW = 'scroll-links-fullview';
    public const MODULE_SCROLL_AUTHORLINKS_FULLVIEW = 'scroll-authorlinks-fullview';
    public const MODULE_SCROLL_LINKS_THUMBNAIL = 'scroll-links-thumbnail';
    public const MODULE_SCROLL_LINKS_LIST = 'scroll-links-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_LINKS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_LINKS_ADDONS],
            [self::class, self::MODULE_SCROLL_LINKS_DETAILS],
            [self::class, self::MODULE_SCROLL_LINKS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_LINKS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_LINKS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_LINKS_LIST],
            [self::class, self::MODULE_SCROLL_AUTHORLINKS_FULLVIEW],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_LINKS_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LINKS_NAVIGATOR],
            self::MODULE_SCROLL_LINKS_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LINKS_ADDONS],
            self::MODULE_SCROLL_LINKS_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LINKS_DETAILS],
            self::MODULE_SCROLL_LINKS_SIMPLEVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LINKS_SIMPLEVIEW],
            self::MODULE_SCROLL_LINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LINKS_FULLVIEW],
            self::MODULE_SCROLL_LINKS_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LINKS_THUMBNAIL],
            self::MODULE_SCROLL_LINKS_LIST => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_LINKS_LIST],
            self::MODULE_SCROLL_AUTHORLINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomScrollInners::class, PoP_ContentPostLinks_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORLINKS_FULLVIEW],
        );

        if ($inner = $inners[$module[1]]) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props)
    {
            
        // Extra classes
        $thumbnails = array(
            [self::class, self::MODULE_SCROLL_LINKS_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_SCROLL_LINKS_LIST],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_LINKS_DETAILS],
        );
        $navigators = array(
            [self::class, self::MODULE_SCROLL_LINKS_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::MODULE_SCROLL_LINKS_ADDONS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_LINKS_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_LINKS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORLINKS_FULLVIEW],
        );
        
        $extra_class = '';
        if (in_array($module, $navigators)) {
            $extra_class = 'navigator text-inverse';
        } elseif (in_array($module, $addons)) {
            $extra_class = 'addons';
        } elseif (in_array($module, $simpleviews)) {
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


        $inner = $this->getInnerSubmodule($module);
        if (in_array($module, $navigators)) {
            // Make it activeItem: highlight on viewing the corresponding fullview
            $this->appendProp($inner, $props, 'class', 'pop-activeitem');
        }
        
        parent::initModelProps($module, $props);
    }
}


