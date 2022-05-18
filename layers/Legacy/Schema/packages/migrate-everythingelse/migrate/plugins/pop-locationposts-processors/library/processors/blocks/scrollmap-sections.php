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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            [self::class, self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP],
            [self::class, self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLLMAP],
            self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
                $tag_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
                return
                    getRouteIcon(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS, true).
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('%s tagged with “#%s”', 'poptheme-wassup'),
                        PoP_LocationPosts_PostNameUtils::getNamesLc(),
                        $applicationtaxonomyapi->getTagSymbolName($tag_id)
                    );
        }

        return parent::getTitle($componentVariation, $props);
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP:
                // Allow URE to add the ContentSource switch
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST];

            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
                return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKPOSTLIST];

            case self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKMAPPOSTLIST];

            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST];

            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST];
        }

        return parent::getControlgroupTopSubmodule($componentVariation);
    }

    public function getLatestcountSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_LOCATIONPOSTS];

            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_AUTHOR_LOCATIONPOSTS];

            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                return [PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::class, PoPThemeWassup_CommonPages_EM_Module_Processor_SectionLatestCounts::MODULE_LATESTCOUNT_TAG_LOCATIONPOSTS];
        }

        return parent::getLatestcountSubmodule($componentVariation);
    }

    protected function getModuleTogglemapanchorcontrolPath(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                $paths = array(
                    self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => array(
                        [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKMAPPOSTLIST],
                        [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLEMAP],
                        [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_TOGGLEMAP],
                    ),
                    self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => array(
                        [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKAUTHORMAPPOSTLIST],
                        [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLEAUTHORMAP],
                        [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_TOGGLEAUTHORMAP],
                    ),
                    self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => array(
                        [PoP_Locations_Module_Processor_CustomControlGroups::class, PoP_Locations_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_BLOCKTAGMAPPOSTLIST],
                        [PoP_Locations_Module_Processor_CustomControlButtonGroups::class, PoP_Locations_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLETAGMAP],
                        [PoP_Locations_Module_Processor_CustomAnchorControls::class, PoP_Locations_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_TOGGLETAGMAP],
                    ),
                );
                return $paths[$componentVariation[1]];
        }

        return null;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                $this->appendProp($componentVariation, $props, 'class', 'block-locationposts-scrollmap');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                if ($path = $this->getModuleTogglemapanchorcontrolPath($componentVariation)) {
                    $this->setProp(
                        $path,
                        $props,
                        'target',
                        '#'.$this->getFrontendId($componentVariation, $props).' > .blocksection-inners .collapse.map'
                    );
                }
                break;
        }

        parent::initWebPlatformModelProps($componentVariation, $props);
    }
}



