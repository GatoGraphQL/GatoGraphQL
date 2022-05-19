<?php
use PoPCMSSchema\Users\ModuleConfiguration as UsersModuleConfiguration;

class GD_EM_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const COMPONENT_BLOCK_SEARCHUSERS_SCROLLMAP = 'block-searchusers-scrollmap';
    public final const COMPONENT_BLOCK_USERS_SCROLLMAP = 'block-users-scrollmap';
    public final const COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP = 'block-users-horizontalscrollmap';
    public final const COMPONENT_BLOCK_EVENTS_SCROLLMAP = 'block-events-scrollmap';
    public final const COMPONENT_BLOCK_PASTEVENTS_SCROLLMAP = 'block-pastevents-scrollmap';
    public final const COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP = 'block-events-horizontalscrollmap';
    public final const COMPONENT_BLOCK_AUTHOREVENTS_SCROLLMAP = 'block-authorevents-scrollmap';
    public final const COMPONENT_BLOCK_AUTHORPASTEVENTS_SCROLLMAP = 'block-authorpastevents-scrollmap';
    public final const COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP = 'block-authorevents-horizontalscrollmap';
    public final const COMPONENT_BLOCK_TAGEVENTS_SCROLLMAP = 'block-tagevents-scrollmap';
    public final const COMPONENT_BLOCK_TAGPASTEVENTS_SCROLLMAP = 'block-tagpastevents-scrollmap';
    public final const COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP = 'block-tagevents-horizontalscrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_EVENTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_PASTEVENTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_SEARCHUSERS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_USERS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_AUTHOREVENTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_AUTHORPASTEVENTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_TAGEVENTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_TAGPASTEVENTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_SEARCHUSERS_SCROLLMAP => POP_BLOG_ROUTE_SEARCHUSERS,
            self::COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP => UsersModuleConfiguration::getUsersRoute(),
            self::COMPONENT_BLOCK_USERS_SCROLLMAP => UsersModuleConfiguration::getUsersRoute(),
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubcomponent(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_SEARCHUSERS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_SEARCHUSERS_SCROLLMAP],
            self::COMPONENT_BLOCK_USERS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_USERS_SCROLLMAP],
            self::COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_USERS_HORIZONTALSCROLLMAP],
            self::COMPONENT_BLOCK_EVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_EVENTS_SCROLLMAP],
            self::COMPONENT_BLOCK_PASTEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_PASTEVENTS_SCROLLMAP],
            self::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_EVENTS_HORIZONTALSCROLLMAP],
            self::COMPONENT_BLOCK_AUTHOREVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_SCROLLMAP],
            self::COMPONENT_BLOCK_AUTHORPASTEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORPASTEVENTS_SCROLLMAP],
            self::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHOREVENTS_HORIZONTALSCROLLMAP],
            self::COMPONENT_BLOCK_TAGEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGEVENTS_SCROLLMAP],
            self::COMPONENT_BLOCK_TAGPASTEVENTS_SCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGPASTEVENTS_SCROLLMAP],
            self::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP => [GD_EM_Module_Processor_CustomScrollMapSectionDataloads::class, GD_EM_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGEVENTS_HORIZONTALSCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_AUTHOREVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORPASTEVENTS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getAuthorTitle();

            case self::COMPONENT_BLOCK_TAGEVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_TAGPASTEVENTS_SCROLLMAP:
                return PoP_Module_Processor_CustomSectionBlocksUtils::getTagTitle();
        }

        return parent::getTitle($component, $props);
    }

    protected function getControlgroupTopSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_PASTEVENTS_SCROLLMAP:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKEVENTLIST];

            case self::COMPONENT_BLOCK_AUTHOREVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORPASTEVENTS_SCROLLMAP:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHOREVENTLIST];

            case self::COMPONENT_BLOCK_TAGEVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_TAGPASTEVENTS_SCROLLMAP:
                return [PoP_Events_Module_Processor_CustomControlGroups::class, PoP_Events_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKTAGEVENTLIST];

            case self::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKMAPPOSTLIST];

            case self::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST];

            case self::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKTAGMAPPOSTLIST];

            case self::COMPONENT_BLOCK_SEARCHUSERS_SCROLLMAP:
            case self::COMPONENT_BLOCK_USERS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKUSERLIST];

            case self::COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKMAPUSERLIST];
        }

        return parent::getControlgroupTopSubcomponent($component);
    }

    protected function getModuleTogglemapanchorcontrolPath(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP:
                $users_events_path = array(
                    $this->getControlgroupTopSubcomponent($component),
                    [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEMAP],
                    [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEMAP],
                );
                $paths = array(
                    self::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP => $users_events_path,
                    self::COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP => $users_events_path,
                    self::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP => array(
                        [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST],
                        [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP],
                        [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEAUTHORMAP],
                    ),
                    self::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP => array(
                        [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKTAGMAPPOSTLIST],
                        [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLETAGMAP],
                        [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLETAGMAP],
                    ),
                );
                return $paths[$component[1]];
        }

        return null;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHOREVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_TAGEVENTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP:
                $this->appendProp($component, $props, 'class', 'block-events-scrollmap');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_USERS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP:
                if ($path = $this->getModuleTogglemapanchorcontrolPath($component)) {
                    $this->setProp(
                        $path,
                        $props,
                        'target',
                        '#'.$this->getFrontendId($component, $props).' > .blocksection-inners .collapse.map'
                    );
                }
                break;
        }

        parent::initWebPlatformModelProps($component, $props);
    }
}



