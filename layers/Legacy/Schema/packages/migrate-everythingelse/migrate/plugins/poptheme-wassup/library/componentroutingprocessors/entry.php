<?php

class PoP_Module_EntryComponentRoutingProcessor extends \PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        // Theme Modes
        $thememode_components = array(
            GD_THEMEMODE_WASSUP_EMBED => [PoP_Module_Processor_Entries::class, PoP_Module_Processor_Entries::MODULE_ENTRY_EMBED],
            GD_THEMEMODE_WASSUP_PRINT => [PoP_Module_Processor_Entries::class, PoP_Module_Processor_Entries::MODULE_ENTRY_PRINT],
        );
        foreach ($thememode_components as $thememode => $component) {
            $ret[] = [
                'component' => $component,
                'conditions' => [
                    'thememode' => $thememode,
                ],
            ];
        }

        // The TopLevel is the entry module by default
        $ret[] = [
            'component' => [PoP_Module_Processor_Entries::class, PoP_Module_Processor_Entries::MODULE_ENTRY_DEFAULT],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
    new PoP_Module_EntryComponentRoutingProcessor()
	);
}, 200);
