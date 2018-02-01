<?php
/**---------------------------------------------------------------------------------------------------------------
 * Specify the fixed targets for certain formats
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('PoP_ResourceLoaderProcessorUtils:resources-from-current-vars:format-targets', 'poptheme_wassup_fixed_format_targets');
function poptheme_wassup_fixed_format_targets($format_targets) {

    return array_merge(
        $format_targets,
        array(
            GD_TEMPLATEFORMAT_ADDONS => GD_URLPARAM_TARGET_ADDONS,
            GD_TEMPLATEFORMAT_NAVIGATOR => GD_URLPARAM_TARGET_NAVIGATOR,
            GD_TEMPLATEFORMAT_MODALS => GD_URLPARAM_TARGET_MODALS,
            GD_TEMPLATEFORMAT_QUICKVIEW => GD_URLPARAM_TARGET_QUICKVIEW,
        )
    );
}