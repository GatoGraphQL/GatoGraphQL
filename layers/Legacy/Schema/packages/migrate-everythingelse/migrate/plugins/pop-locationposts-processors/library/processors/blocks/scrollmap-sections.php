<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_Custom_Module_Processor_CustomScrollMapSectionBlocks extends GD_EM_Module_Processor_ScrollMapBlocksBase
{
    public const MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP = 'block-locationposts-scrollmap';
    public const MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP = 'block-locationposts-horizontalscrollmap';
    public const MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP = 'block-authorlocationposts-scrollmap';
    public const MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'block-authorlocationposts-horizontalscrollmap';
    public const MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP = 'block-taglocationposts-scrollmap';
    public const MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP = 'block-taglocationposts-horizontalscrollmap';

    public function getModulesToProcess(): array
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

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP => POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodule(array $module)
    {
        $inner_modules = array(
            self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_SCROLLMAP],
            self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_SCROLLMAP],
            self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_SCROLLMAP],
            self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP => [GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::class, GD_Custom_Module_Processor_CustomScrollMapSectionDataloads::MODULE_DATALOAD_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );

        return $inner_modules[$module[1]] ?? null;
    }

    public function getTitle(array $module, array &$props)
    {
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
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

        return parent::getTitle($module, $props);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
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

        return parent::getControlgroupTopSubmodule($module);
    }

    public function getLatestcountSubmodule(array $module)
    {
        switch ($module[1]) {
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

        return parent::getLatestcountSubmodule($module);
    }

    protected function getModuleTogglemapanchorcontrolPath(array $module)
    {
        switch ($module[1]) {
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
                return $paths[$module[1]];
        }

        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                $this->appendProp($module, $props, 'class', 'block-locationposts-scrollmap');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP:
            case self::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP:
                if ($path = $this->getModuleTogglemapanchorcontrolPath($module)) {
                    $this->setProp(
                        $path,
                        $props,
                        'target',
                        '#'.$this->getFrontendId($module, $props).' > .blocksection-inners .collapse.map'
                    );
                }
                break;
        }

        parent::initWebPlatformModelProps($module, $props);
    }
}



