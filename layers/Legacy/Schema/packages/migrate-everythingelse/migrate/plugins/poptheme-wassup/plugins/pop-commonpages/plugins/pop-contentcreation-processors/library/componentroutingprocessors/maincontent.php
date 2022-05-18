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

        $componentVariations = array(
            // Commented because that's the default module for a page, so no need to define it here
            // POP_COMMONPAGES_PAGE_ABOUT_CONTENTGUIDELINES => [PoP_Module_Processor_CustomContentBlocks::class, PoP_Module_Processor_CustomContentBlocks::MODULE_BLOCK_PAGE_CONTENT],
            POP_COMMONPAGES_PAGE_ADDCONTENTFAQ => [GD_CommonPages_Module_Processor_CustomBlocks::class, GD_CommonPages_Module_Processor_CustomBlocks::MODULE_BLOCK_ADDCONTENTFAQ],
        );
        foreach ($componentVariations as $page => $componentVariation) {
            $ret[PageRequestNature::PAGE][] = [
                'component-variation' => $componentVariation,
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
