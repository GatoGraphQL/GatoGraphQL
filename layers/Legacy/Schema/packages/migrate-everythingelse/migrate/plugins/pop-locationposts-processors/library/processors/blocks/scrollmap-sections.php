<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public final const MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP = 'block-locationposts-scrollmap';
    public final const MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP = 'block-locationposts-horizontalscrollmap';
    public final const MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP = 'block-authorlocationposts-scrollmap';
    public final const MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'block-authorlocationposts-horizontalscrollmap';
    public final const MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP = 'block-taglocationposts-scrollmap';
    public final const MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'block-taglocationposts-horizontalscrollmap';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    protected function getInnerSubmodule(array $component)
    {
        $inner_components = array(
            self::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_LOCATIONPOSTS_SCROLLMAP],
            self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP],
            self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP],
            self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::COMPONENT_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );

        return $inner_components[$component[1]] ?? null;
    }

    public function getTitle(array $component, array &$props)
    {
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
                $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return
                    getRouteIcon(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS, true).
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('%s tagged with “#%s”', 'poptheme-wassup'),
                        PoP_LocationPosts_PostNameUtils::getNamesLc(),
                        $applicationtaxonomyapi->getTagSymbolName($tag_id)
                    );
        }

        return parent::getTitle($component, $props);
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP:
                // Allow URE to add the ContentSource switch
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKPOSTLIST];

            case self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKMAPPOSTLIST];

            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST];

            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKTAGMAPPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($component);
    }

    public function getLatestcountSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_LOCATIONPOSTS];

            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_AUTHOR_LOCATIONPOSTS];

            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::COMPONENT_LATESTCOUNT_TAG_LOCATIONPOSTS];
        }

        return parent::getLatestcountSubmodule($component);
    }

    protected function getModuleTogglemapanchorcontrolPath(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                $paths = array(
                    self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => array(
                        [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKMAPPOSTLIST],
                        [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEMAP],
                        [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEMAP],
                    ),
                    self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => array(
                        [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::COMPONENT_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST],
                        [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP],
                        [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::COMPONENT_ANCHORCONTROL_TOGGLEAUTHORMAP],
                    ),
                    self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => array(
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
            case self::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                $this->appendProp($component, $props, 'class', 'block-locationposts-scrollmap');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
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



