<?php

class GD_Custom_EM_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW = 'scroll-mylocationposts-fullviewpreview';
    public final const COMPONENT_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW = 'scroll-mylocationposts-simpleviewpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
        );
    }


    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW => [GD_EM_Custom_Module_Processor_CustomScrollInners::class, GD_EM_Custom_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
            self::COMPONENT_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW => [GD_EM_Custom_Module_Processor_CustomScrollInners::class, GD_EM_Custom_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_THUMBNAIL],
        );
        $lists = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_LIST],
        );
        $details = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_DETAILS],
        );
        $navigators = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_NAVIGATOR],
        );
        $addons = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::COMPONENT_SCROLL_LOCATIONPOSTS_ADDONS],
        );

        $extra_class = '';
        if (in_array($component, $navigators)) {
            $extra_class = 'navigator text-inverse';
        } elseif (in_array($component, $addons)) {
            $extra_class = 'addons';
        } elseif (in_array($component, $details)) {
            $extra_class = 'details';
        } elseif (in_array($component, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($component, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($component, $props, 'class', $extra_class);


        $inner = $this->getInnerSubcomponent($component);
        if (in_array($component, $navigators)) {
            // Make it activeItem: highlight on viewing the corresponding fullview
            $this->appendProp($inner, $props, 'class', 'pop-activeitem');
        }

        parent::initModelProps($component, $props);
    }
}


