<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_SP_EM_Bootstrap_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $routemodules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_SectionTabPanelBlocks::class, PoP_LocationPosts_Module_Processor_SectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_LOCATIONPOSTS],
        );
        foreach ($routemodules as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_AuthorSectionTabPanelBlocks::class, PoP_LocationPosts_Module_Processor_AuthorSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_AUTHORLOCATIONPOSTS],
        );
        foreach ($routemodules as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        $routemodules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_TagSectionTabPanelBlocks::class, PoP_LocationPosts_Module_Processor_TagSectionTabPanelBlocks::COMPONENT_BLOCK_TABPANEL_TAGLOCATIONPOSTS],
        );
        foreach ($routemodules as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
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
		new PoPTheme_Wassup_SP_EM_Bootstrap_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
