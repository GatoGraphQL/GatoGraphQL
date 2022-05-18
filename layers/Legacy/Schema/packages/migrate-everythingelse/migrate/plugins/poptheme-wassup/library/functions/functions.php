<?php

\PoP\Root\App::addFilter('pop_componentmanager:userloggedin_loadingmsg_target', 'gdCustomUserloggedinLoadingmsgTarget');
function gdCustomUserloggedinLoadingmsgTarget($target)
{

    // Element placed in frame-top.tmpl
    return '#account-loading-msg';
}


/**
 * Uniqueblocks
 */

\PoP\Root\App::addFilter('RequestUtils:getFramecomponentModules', 'getWassupFramecomponentModules');
function getWassupFramecomponentComponents($components)
{
    if (\PoP\Root\App::applyFilters('poptheme_wassup_loadlatest', true)) {
        $components[] = [GD_Core_Module_Processor_Blocks::class, GD_Core_Module_Processor_Blocks::MODULE_MULTIPLE_LATESTCOUNTS];
    }

    return $components;
}


\PoP\Root\App::addFilter('pop_componentmanager:fetchtarget_settings', 'gdCustomFetchtargetSettings');
function gdCustomFetchtargetSettings($fetchtarget_settings)
{
    return array_merge(
        $fetchtarget_settings,
        array(
            // \PoP\ConfigurationComponentModel\Constants\Targets::MAIN => POP_MODULEID_PAGESECTIONCONTAINERID_BODY, // Since creating targets "body", "hover" and "hole", no need for this one anymore, since this case will not happen
            POP_TARGET_BODY => POP_MODULEID_PAGESECTIONCONTAINERID_BODY,
            POP_TARGET_HOVER => POP_MODULEID_PAGESECTIONCONTAINERID_HOVER,
            POP_TARGET_HOLE => POP_MODULEID_PAGESECTIONCONTAINERID_HOLE,
            POP_TARGET_QUICKVIEW => POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEW,
            POP_TARGET_NAVIGATOR => POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR,
            POP_TARGET_ADDONS => POP_MODULEID_PAGESECTIONCONTAINERID_ADDONS,
            POP_TARGET_MODALS => POP_MODULEID_PAGESECTIONCONTAINERID_MODALS,
            POP_TARGET_TOP => POP_MODULEID_PAGESECTIONCONTAINERID_TOP,
            POP_TARGET_BACKGROUND => POP_MODULEID_PAGESECTIONCONTAINERID_BACKGROUND,
            POP_TARGET_SIDE => POP_MODULEID_PAGESECTIONCONTAINERID_SIDE,
            POP_TARGET_BODYSIDEINFO => POP_MODULEID_PAGESECTIONCONTAINERID_BODYSIDEINFO,
            POP_TARGET_QUICKVIEWSIDEINFO => POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEWSIDEINFO,
            POP_TARGET_FRAMECOMPONENTS => POP_MODULEID_PAGESECTIONCONTAINERID_FRAMECOMPONENTS,
        )
    );
}

// \PoP\Root\App::addFilter('PoP_Module_Processor_MenuMultiplesBase:js-setting:add-active-parent-item', 'popAddMenuitemParentpageActive', 10, 3);
// function popAddMenuitemParentpageActive($add_active, array $component, array &$props) {

//     // Only if not in Side or Top pageSections
//     $pagesection_settings_id = $props['pagesection-moduleoutputname'];
//     $include = array(
//         [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODY],
//         [PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_BODYSIDEINFO],
//     );
//     if (in_array($pagesection_settings_id, $include)) {

//         return true;
//     }

//     return $add_active;
// }

\PoP\Root\App::addFilter('pop_componentmanager:fetchpagesection_settings', 'gdCustomFetchpagesectionSettings');
function gdCustomFetchpagesectionSettings($fetchpagesection_settings)
{
    $settings_main = array(
        'operation' => GD_URLPARAM_OPERATION_APPEND,
        'noparams-reload-url' => true,
        'updateDocument' => true,
        'maybeRedirect' => true,
        // 'activeLinks' => true,
    );
    $settings_append = array(
        'operation' => GD_URLPARAM_OPERATION_APPEND,
    );
    $settings_activeappend = array(
        'operation' => GD_URLPARAM_OPERATION_APPEND,
        // 'activeLinks' => true,
    );

    return array_merge(
        $fetchpagesection_settings,
        array(
            POP_MODULEID_PAGESECTIONCONTAINERID_BODY => $settings_main,
            POP_MODULEID_PAGESECTIONCONTAINERID_HOVER => $settings_main,
            POP_MODULEID_PAGESECTIONCONTAINERID_BODYTABS => $settings_append, //$settings_main,
            POP_MODULEID_PAGESECTIONCONTAINERID_BODYSIDEINFO => $settings_activeappend,
            POP_MODULEID_PAGESECTIONCONTAINERID_ADDONTABS => $settings_append,
            POP_MODULEID_PAGESECTIONCONTAINERID_ADDONS => $settings_append,
            POP_MODULEID_PAGESECTIONCONTAINERID_MODALS => $settings_append,
            POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR => $settings_append,
            POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEW => $settings_append,
            POP_MODULEID_PAGESECTIONCONTAINERID_QUICKVIEWSIDEINFO => $settings_append,
            POP_MODULEID_PAGESECTIONCONTAINERID_HOLE => $settings_append, // Operational: do not update browser URL. Eg: for "Follow user" page
            POP_MODULEID_PAGESECTIONCONTAINERID_FRAMECOMPONENTS => $settings_append, // Operational: do not update browser URL. Eg: for "Follow user" page
        )
    );
}


/**
 * Targets
 */
\PoP\Root\App::addFilter('ApplicationState:targets', 'getCustomTargets');
function getCustomTargets($targets)
{
    return array_merge(
        $targets,
        array(
            POP_TARGET_BODY,
            POP_TARGET_HOVER,
            POP_TARGET_HOLE,
            POP_TARGET_QUICKVIEW,
            POP_TARGET_NAVIGATOR,
            POP_TARGET_ADDONS,
            POP_TARGET_MODALS,
            POP_TARGET_TOP,
            POP_TARGET_BACKGROUND,
            POP_TARGET_SIDE,
            POP_TARGET_BODYSIDEINFO,
            POP_TARGET_FRAMECOMPONENTS,
        )
    );
}
