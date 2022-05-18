<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Module_MainPageSectionComponentRoutingProcessor extends PoP_Module_MainPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // Author
        $components = array(
            POP_ROUTE_DESCRIPTION => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_AUTHORDESCRIPTION],
            POPTHEME_WASSUP_ROUTE_SUMMARY => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_AUTHORSUMMARY],
        );
        foreach ($components as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = ['component' => $component];
        }

        // Override default module
        $routes = array(
            POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES,
        );
        foreach ($routes as $route) {
            // Override the default Page Content module
            $ret[RequestNature::GENERIC][$route][] = ['component' => null];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        // 404
        $ret[RequestNature::NOTFOUND][] = [
            'component' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_404]
        ];

        // Home
        $ret[RequestNature::HOME][] = [
            'component' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_HOME]
        ];

        // Author
        $ret[UserRequestNature::USER][] = [
            'component' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_AUTHOR]
        ];

        // Tag
        $ret[TagRequestNature::TAG][] = [
            'component' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_TAG]
        ];

        // Single
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'component' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::COMPONENT_BLOCK_SINGLEPOST]
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Module_MainPageSectionComponentRoutingProcessor()
	);
}, 200);
