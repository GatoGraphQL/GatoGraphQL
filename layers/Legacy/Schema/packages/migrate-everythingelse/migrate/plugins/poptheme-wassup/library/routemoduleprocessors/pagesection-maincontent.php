<?php

use PoP\Root\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoP_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // Author
        $modules = array(
            POP_ROUTE_DESCRIPTION => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_AUTHORDESCRIPTION],
            POPTHEME_WASSUP_ROUTE_SUMMARY => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_AUTHORSUMMARY],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
        }

        // Override default module
        $routes = array(
            POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES,
        );
        foreach ($routes as $route) {
            // Override the default Page Content module
            $ret[RouteNatures::GENERIC][$route][] = ['module' => null];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        // 404
        $ret[RouteNatures::NOTFOUND][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_404]
        ];

        // Home
        $ret[RouteNatures::HOME][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_HOME]
        ];

        // Author
        $ret[UserRouteNatures::USER][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_AUTHOR]
        ];

        // Tag
        $ret[TagRouteNatures::TAG][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_TAG]
        ];

        // Single
        $ret[CustomPostRouteNatures::CUSTOMPOST][] = [
            'module' => [PoP_Module_Processor_MainBlocks::class, PoP_Module_Processor_MainBlocks::MODULE_BLOCK_SINGLEPOST]
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
