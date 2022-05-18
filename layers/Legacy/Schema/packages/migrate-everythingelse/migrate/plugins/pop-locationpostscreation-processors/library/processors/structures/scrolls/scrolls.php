<?php

class GD_Custom_EM_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW = 'scroll-mylocationposts-fullviewpreview';
    public final const MODULE_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW = 'scroll-mylocationposts-simpleviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW => [GD_EM_Custom_Module_Processor_CustomScrollInners::class, GD_EM_Custom_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYLOCATIONPOSTS_SIMPLEVIEWPREVIEW],
            self::MODULE_SCROLL_MYLOCATIONPOSTS_FULLVIEWPREVIEW => [GD_EM_Custom_Module_Processor_CustomScrollInners::class, GD_EM_Custom_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYLOCATIONPOSTS_FULLVIEWPREVIEW],
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
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_THUMBNAIL],
        );
        $lists = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_LIST],
        );
        $details = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_DETAILS],
        );
        $navigators = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_NAVIGATOR],
        );
        $addons = array(
            [PoP_LocationPosts_Module_Processor_CustomScrolls::class, PoP_LocationPosts_Module_Processor_CustomScrolls::MODULE_SCROLL_LOCATIONPOSTS_ADDONS],
        );

        $extra_class = '';
        if (in_array($module, $navigators)) {
            $extra_class = 'navigator text-inverse';
        } elseif (in_array($module, $addons)) {
            $extra_class = 'addons';
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


