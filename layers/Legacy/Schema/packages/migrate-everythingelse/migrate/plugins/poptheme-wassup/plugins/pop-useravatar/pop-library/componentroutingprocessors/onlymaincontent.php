<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserAvatar_Module_OnlyMainContentComponentRoutingProcessor extends PoP_Module_OnlyMainContentComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        // If having ?action=execute, then choose the "execute" module
        // Two different actions, handled through $moduleAtts:
        // 1. Upload the image to the S3 repository, when first accessing the page
        // 2. Update the avatar, on the POST operation
        $module = [PoP_UserAvatarProcessors_Module_Processor_UserDataloads::class, PoP_UserAvatarProcessors_Module_Processor_UserDataloads::MODULE_DATALOAD_USERAVATAR_UPDATE];
        $ret[RequestNature::GENERIC][POP_USERAVATAR_ROUTE_EDITAVATAR][] = [
            'module' => $module,
        ];
        $ret[RequestNature::GENERIC][POP_USERAVATAR_ROUTE_EDITAVATAR][] = [
            'module' => [
                $module[0],
                $module[1],
                [
                    'executeupdate' => true,
                ],
            ],
            'conditions' => [
                'action' => POP_ACTION_USERAVATAR_EXECUTEUPDATE,
            ],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_UserAvatar_Module_OnlyMainContentComponentRoutingProcessor()
	);
}, 200);
