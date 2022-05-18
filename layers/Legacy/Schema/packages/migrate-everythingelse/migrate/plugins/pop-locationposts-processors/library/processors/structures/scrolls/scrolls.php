<?php

class PoP_LocationPosts_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_LOCATIONPOSTS_NAVIGATOR = 'scroll-locationposts-navigator';
    public final const MODULE_SCROLL_LOCATIONPOSTS_ADDONS = 'scroll-locationposts-addons';
    public final const MODULE_SCROLL_LOCATIONPOSTS_DETAILS = 'scroll-locationposts-details';
    public final const MODULE_SCROLL_LOCATIONPOSTS_SIMPLEVIEW = 'scroll-locationposts-simpleview';
    public final const MODULE_SCROLL_LOCATIONPOSTS_FULLVIEW = 'scroll-locationposts-fullview';
    public final const MODULE_SCROLL_AUTHORLOCATIONPOSTS_FULLVIEW = 'scroll-authorlocationposts-fullview';
    public final const MODULE_SCROLL_LOCATIONPOSTS_THUMBNAIL = 'scroll-locationposts-thumbnail';
    public final const MODULE_SCROLL_LOCATIONPOSTS_LIST = 'scroll-locationposts-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_ADDONS],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_DETAILS],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_LIST],
            [self::class, self::COMPONENT_SCROLL_AUTHORLOCATIONPOSTS_FULLVIEW],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_LOCATIONPOSTS_NAVIGATOR => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_NAVIGATOR],
            self::COMPONENT_SCROLL_LOCATIONPOSTS_ADDONS => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_ADDONS],
            self::COMPONENT_SCROLL_LOCATIONPOSTS_DETAILS => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_DETAILS],
            self::COMPONENT_SCROLL_LOCATIONPOSTS_SIMPLEVIEW => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_SIMPLEVIEW],
            self::COMPONENT_SCROLL_LOCATIONPOSTS_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_FULLVIEW],
            self::COMPONENT_SCROLL_LOCATIONPOSTS_THUMBNAIL => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_THUMBNAIL],
            self::COMPONENT_SCROLL_LOCATIONPOSTS_LIST => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_LOCATIONPOSTS_LIST],
            self::COMPONENT_SCROLL_AUTHORLOCATIONPOSTS_FULLVIEW => [PoP_LocationPosts_Module_Processor_CustomScrollInners::class, PoP_LocationPosts_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_AUTHORLOCATIONPOSTS_FULLVIEW],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $simpleviews = array(
            [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::COMPONENT_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_SIMPLEVIEW],
        );
        $fullviews = array(
            [GD_Custom_EM_Module_Processor_CustomScrolls::class, GD_Custom_EM_Module_Processor_CustomScrolls::COMPONENT_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_LOCATIONPOSTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLL_AUTHORLOCATIONPOSTS_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($component, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        }
        $this->appendProp($component, $props, 'class', $extra_class);


        parent::initModelProps($component, $props);
    }
}


