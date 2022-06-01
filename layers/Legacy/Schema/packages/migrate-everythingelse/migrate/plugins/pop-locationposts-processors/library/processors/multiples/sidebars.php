<?php
use PoP\ComponentModel\State\ApplicationState;

class PoPSP_URE_EM_Module_Processor_SidebarMultiples extends PoP_Module_Processor_SidebarMultiplesBase
{
    public final const COMPONENT_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR = 'multiple-authorlocationposts-sidebar';
    public final const COMPONENT_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR = 'multiple-section-locationposts-sidebar';
    public final const COMPONENT_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR = 'multiple-tag-locationposts-sidebar';
    public final const COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR = 'multiple-single-locationpost-sidebar';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR,
            self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR,
        );
    }

    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR:
                $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                $filters = array(
                    self::COMPONENT_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR => [GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::class, GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_AUTHORLOCATIONPOSTS_SIDEBAR],
                );
                $ret[] = $filters[$component->name];

                $ret = \PoP\Root\App::applyFilters(
                    'PoPSP_URE_EM_Module_Processor_SidebarMultiples:inner-modules:author',
                    $ret
                );
                break;

            default:
                $inners = array(
                    self::COMPONENT_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR => [GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::class, GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_LOCATIONPOSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR => [GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::class, GD_Custom_EM_Module_Processor_CustomSectionSidebarInners::COMPONENT_MULTIPLE_SECTIONINNER_TAGLOCATIONPOSTS_SIDEBAR],
                    self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR => [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR],
                );
                if ($inner = $inners[$component->name] ?? null) {
                    $ret[] = $inner;
                }
                break;
        }

        return $ret;
    }

    public function getScreen(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_AUTHORSECTION;

            case self::COMPONENT_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_SECTION;

            case self::COMPONENT_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR:
                return POP_SCREEN_TAGSECTION;

            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR:
                return POP_SCREEN_SINGLE;
        }

        return parent::getScreen($component);
    }

    public function getScreengroup(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;

            case self::COMPONENT_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR:
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR:
                return POP_SCREENGROUP_CONTENTREAD;
        }

        return parent::getScreengroup($component);
    }

    public function initWebPlatformModelProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR:
                $inners = array(
                    self::COMPONENT_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR => [PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::class, PoP_LocationPosts_Module_Processor_CustomSidebarDataloads::COMPONENT_DATALOAD_SINGLE_LOCATIONPOST_SIDEBAR],
                );
                $subcomponent = $inners[$component->name];

                // Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
                $mainblock_taget = '#'.POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY.' .pop-pagesection-page.toplevel:last-child > .blockgroup-singlepost > .blocksection-extensions > .pop-block > .blocksection-inners .content-single';

                // Make the block be collapsible, open it when the main feed is reached, with waypoints
                $this->appendProp([$subcomponent], $props, 'class', 'collapse');
                $this->mergeProp(
                    [$subcomponent],
                    $props,
                    'params',
                    array(
                        'data-collapse-target' => $mainblock_taget
                    )
                );
                $this->mergeJsmethodsProp([$subcomponent], $props, array('waypointsToggleCollapse'));
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}


