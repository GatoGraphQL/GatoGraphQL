<?php

use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;

class PoP_CommonPages_ContentCreation_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $components = array(
            // Commented because that's the default module for a page, so no need to define it here
            // POP_COMMONPAGES_PAGE_ABOUT_CONTENTGUIDELINES => [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::COMPONENT_BLOCK_PAGE_CONTENT],
            POP_COMMONPAGES_PAGE_ADDCONTENTFAQ => [GD_CommonPages_Module_Processor_CustomBlocks::class, GD_CommonPages_Module_Processor_CustomBlocks::COMPONENT_BLOCK_ADDCONTENTFAQ],
        );
        foreach ($components as $page => $component) {
            $ret[PageRequestNature::PAGE][] = [
                'component' => $component,
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
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_CommonPages_ContentCreation_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
