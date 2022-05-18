<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_AppCatPro_Bootstrap_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS00],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS01],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS02],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS03],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS04],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS05],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS06],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS07],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS08],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS09],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS10],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS11],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS12],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS13],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS14],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS15],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS16],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS17],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS18],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_TabPanelSectionBlocks::class, CPP_Module_Processor_TabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_CATEGORYPOSTS19],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS00],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS01],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS02],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS03],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS04],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS05],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS06],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS07],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS08],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS09],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS10],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS11],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS12],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS13],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS14],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS15],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS16],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS17],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS18],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_AuthorTabPanelSectionBlocks::class, CPP_Module_Processor_AuthorTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCATEGORYPOSTS19],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS00],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS01],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS02],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS03],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS04],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS05],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS06],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS07],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS08],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS09],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS10],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS11],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS12],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS13],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS14],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS15],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS16],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS17],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS18],
            POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19 => [CPP_Module_Processor_TagTabPanelSectionBlocks::class, CPP_Module_Processor_TagTabPanelSectionBlocks::MODULE_BLOCK_TABPANEL_TAGCATEGORYPOSTS19],
        );
        foreach ($routemodules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_AppCatPro_Bootstrap_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
