<?php

use PoP\Root\Routing\RouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoPTheme_Wassup_Blog_Module_OnlyMainContentRouteModuleProcessor extends PoP_Module_OnlyMainContentRouteModuleProcessorBase
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        // Home modules
        $format_modules = array(
            POP_FORMAT_DETAILS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_HOMECONTENT_SCROLL_DETAILS],
            POP_FORMAT_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_HOMECONTENT_SCROLL_SIMPLEVIEW],
            POP_FORMAT_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_HOMECONTENT_SCROLL_FULLVIEW],
            POP_FORMAT_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_HOMECONTENT_SCROLL_THUMBNAIL],
            POP_FORMAT_LIST => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_HOMECONTENT_SCROLL_LIST],
        );
        foreach ($format_modules as $format => $module) {
            $ret[RouteNatures::HOME][] = [
                'module' => $module,
                'conditions' => [
                    'format' => $format,
                ],
            ];
            if ($default_format_section == $format) {
                $ret[RouteNatures::HOME][] = [
                    'module' => $module,
                ];
            }
        }

        // Author route blocks
        $format_modules = array(
            POP_FORMAT_DETAILS => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_DETAILS],
            POP_FORMAT_SIMPLEVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_SIMPLEVIEW],
            POP_FORMAT_FULLVIEW => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_FULLVIEW],
            POP_FORMAT_THUMBNAIL => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_THUMBNAIL],
            POP_FORMAT_LIST => [PoP_Blog_Module_Processor_CustomSectionBlocks::class, PoP_Blog_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORCONTENT_SCROLL_LIST],
        );
        foreach ($format_modules as $format => $module) {
            $ret[UserRouteNatures::USER][] = [
                'module' => $module,
                'conditions' => [
                    'format' => $format,
                ],
            ];
            if ($default_format_section == $format) {
                $ret[UserRouteNatures::USER][] = [
                    'module' => $module,
                ];
            }
        }

        // Tag modules
        $format_modules = array(
            POP_FORMAT_DETAILS => [PoPTheme_Wassup_Blog_Module_Processor_Groups::class, PoPTheme_Wassup_Blog_Module_Processor_Groups::MODULE_GROUP_TAGCONTENT_SCROLL_DETAILS],
            POP_FORMAT_SIMPLEVIEW => [PoPTheme_Wassup_Blog_Module_Processor_Groups::class, PoPTheme_Wassup_Blog_Module_Processor_Groups::MODULE_GROUP_TAGCONTENT_SCROLL_SIMPLEVIEW],
            POP_FORMAT_FULLVIEW => [PoPTheme_Wassup_Blog_Module_Processor_Groups::class, PoPTheme_Wassup_Blog_Module_Processor_Groups::MODULE_GROUP_TAGCONTENT_SCROLL_FULLVIEW],
            POP_FORMAT_THUMBNAIL => [PoPTheme_Wassup_Blog_Module_Processor_Groups::class, PoPTheme_Wassup_Blog_Module_Processor_Groups::MODULE_GROUP_TAGCONTENT_SCROLL_THUMBNAIL],
            POP_FORMAT_LIST => [PoPTheme_Wassup_Blog_Module_Processor_Groups::class, PoPTheme_Wassup_Blog_Module_Processor_Groups::MODULE_GROUP_TAGCONTENT_SCROLL_LIST],
        );
        foreach ($format_modules as $format => $module) {
            $ret[TagRouteNatures::TAG][] = [
                'module' => $module,
                'conditions' => [
                    'format' => $format,
                ],
            ];
            if ($default_format_section == $format) {
                $ret[TagRouteNatures::TAG][] = [
                    'module' => $module,
                ];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_Blog_Module_OnlyMainContentRouteModuleProcessor()
	);
}, 200);
