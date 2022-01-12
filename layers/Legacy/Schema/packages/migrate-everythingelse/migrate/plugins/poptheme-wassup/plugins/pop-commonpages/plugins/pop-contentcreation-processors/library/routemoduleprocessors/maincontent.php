<?php

use PoPSchema\Pages\Routing\RouteNatures as PageRouteNatures;

class PoP_CommonPages_ContentCreation_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $modules = array(
            // Commented because that's the default module for a page, so no need to define it here
            // POP_COMMONPAGES_PAGE_ABOUT_CONTENTGUIDELINES => [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_PAGE_CONTENT],
            POP_COMMONPAGES_PAGE_ADDCONTENTFAQ => [GD_CommonPages_Module_Processor_CustomBlocks::class, GD_CommonPages_Module_Processor_CustomBlocks::MODULE_BLOCK_ADDCONTENTFAQ],
        );
        foreach ($modules as $page => $module) {
            $ret[PageRouteNatures::PAGE][] = [
                'module' => $module,
                'conditions' => [
                    'routing' => [
                        'queried-object-id' => $page,
                    ],
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_CommonPages_ContentCreation_Module_MainContentRouteModuleProcessor()
	);
}, 200);
