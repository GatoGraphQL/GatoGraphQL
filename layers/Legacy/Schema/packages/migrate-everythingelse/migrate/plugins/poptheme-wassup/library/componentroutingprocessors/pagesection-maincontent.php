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
        $modules = array(
            POP_ROUTE_DESCRIPTION => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_AUTHORDESCRIPTION],
            POPTHEME_WASSUP_ROUTE_SUMMARY => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_AUTHORSUMMARY],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = ['module' => $module];
        }

        // Override default module
        $routes = array(
            POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES,
        );
        foreach ($routes as $route) {
            // Override the default Page Content module
            $ret[RequestNature::GENERIC][$route][] = ['module' => null];
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
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_404]
        ];

        // Home
        $ret[RequestNature::HOME][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_HOME]
        ];

        // Author
        $ret[UserRequestNature::USER][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_AUTHOR]
        ];

        // Tag
        $ret[TagRequestNature::TAG][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_TAG]
        ];

        // Single
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_SINGLEPOST]
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
