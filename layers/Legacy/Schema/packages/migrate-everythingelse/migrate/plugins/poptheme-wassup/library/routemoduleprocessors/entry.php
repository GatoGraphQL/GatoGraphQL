<?php

class PoP_Module_EntryRouteModuleProcessor extends \PoP\ComponentRouting\AbstractEntryRouteModuleProcessor
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        // Theme Modes
        $thememode_modules = array(
            GD_THEMEMODE_WASSUP_EMBED => [PoP_Module_Processor_Entries::class, PoP_Module_Processor_Entries::MODULE_ENTRY_EMBED],
            GD_THEMEMODE_WASSUP_PRINT => [PoP_Module_Processor_Entries::class, PoP_Module_Processor_Entries::MODULE_ENTRY_PRINT],
        );
        foreach ($thememode_modules as $thememode => $module) {
            $ret[] = [
                'module' => $module,
                'conditions' => [
                    'thememode' => $thememode,
                ],
            ];
        }

        // The TopLevel is the entry module by default
        $ret[] = [
            'module' => [PoP_Module_Processor_Entries::class, PoP_Module_Processor_Entries::MODULE_ENTRY_DEFAULT],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
    new PoP_Module_EntryRouteModuleProcessor()
	);
}, 200);
