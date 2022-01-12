<?php

use PoPSchema\Pages\Routing\RouteNatures as PageRouteNatures;

class PoP_CommonPages_UserPlatform_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $modules = array(
            POP_COMMONPAGES_PAGE_ACCOUNTFAQ => [GD_CommonPages_Module_Processor_CustomBlocks::class, GD_CommonPages_Module_Processor_CustomBlocks::MODULE_BLOCK_ACCOUNTFAQ],
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
		new PoP_CommonPages_UserPlatform_Module_MainContentRouteModuleProcessor()
	);
}, 200);
