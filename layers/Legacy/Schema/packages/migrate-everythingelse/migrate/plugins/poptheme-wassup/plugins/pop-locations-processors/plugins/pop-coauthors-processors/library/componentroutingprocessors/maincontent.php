<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;

class Wassup_EM_CAP_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routemodules_map = array(
            POP_ROUTE_AUTHORS => [PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_CoAuthors_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_SINGLEAUTHORS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new Wassup_EM_CAP_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
