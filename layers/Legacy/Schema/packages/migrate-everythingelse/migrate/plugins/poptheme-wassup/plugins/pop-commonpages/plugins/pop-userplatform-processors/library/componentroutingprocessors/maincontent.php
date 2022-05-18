<?php

use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;

class PoP_CommonPages_UserPlatform_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        $ret = array();

        $modules = array(
            POP_COMMONPAGES_PAGE_ACCOUNTFAQ => [GD_CommonPages_Module_Processor_CustomBlocks::class, GD_CommonPages_Module_Processor_CustomBlocks::MODULE_BLOCK_ACCOUNTFAQ],
        );
        foreach ($modules as $page => $module) {
            $ret[PageRequestNature::PAGE][] = [
                'component-variation' => $module,
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
		new PoP_CommonPages_UserPlatform_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
