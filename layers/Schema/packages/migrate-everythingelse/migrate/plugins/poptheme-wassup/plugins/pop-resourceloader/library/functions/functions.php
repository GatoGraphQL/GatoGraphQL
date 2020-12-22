<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/**
 * Specify the fixed targets for certain formats
 */
HooksAPIFacade::getInstance()->addFilter('PoP_ResourceLoaderProcessorUtils:resources-from-current-vars:format-targets', 'popthemeWassupFixedFormatTargets');
function popthemeWassupFixedFormatTargets($format_targets)
{
    return array_merge(
        $format_targets,
        array(
            POP_FORMAT_ADDONS => POP_TARGET_ADDONS,
            POP_FORMAT_NAVIGATOR => POP_TARGET_NAVIGATOR,
            POP_FORMAT_MODALS => POP_TARGET_MODALS,
            POP_FORMAT_QUICKVIEW => POP_TARGET_QUICKVIEW,
        )
    );
}
