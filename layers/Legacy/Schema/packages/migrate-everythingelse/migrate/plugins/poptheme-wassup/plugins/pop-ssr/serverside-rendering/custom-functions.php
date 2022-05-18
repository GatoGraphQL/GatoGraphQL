<?php
class Wassup_ServerSide_CustomFunctions
{

    //-------------------------------------------------
    // PUBLIC FUNCTIONS
    //-------------------------------------------------

    public function addPageSectionIds(&$args)
    {
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $popJSRuntimeManager = PoP_ServerSide_LibrariesFactory::getJsruntimeInstance();

        $domain = $args['domain'];
        $pageSection = $args['pageSection'];
        $moduleName = $args['component'];
        $pssId = $popManager->getSettingsId($pageSection);
        // $psId = $pageSection->attr('id');

        // if ($psId == POP_MODULEID_PAGESECTIONCONTAINERID_HOVER) {
        if ($pssId == PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_HOVER) {
            $psId = POP_MODULEID_PAGESECTIONCONTAINERID_HOVER;
            $popJSRuntimeManager->addPageSectionId($domain, $pssId, $moduleName, $psId, 'closehover');
        }
        // else if ($psId == POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR) {
        elseif ($pssId == PoP_Module_Processor_PageSections::COMPONENT_PAGESECTION_NAVIGATOR) {
            $psId = POP_MODULEID_PAGESECTIONCONTAINERID_NAVIGATOR;
            $popJSRuntimeManager->addPageSectionId($domain, $pssId, $moduleName, $psId, 'closenavigator');
        } elseif ($pssId == PoP_Module_Processor_TabPanes::COMPONENT_PAGESECTION_ADDONS) {
            $psId = POP_MODULEID_PAGESECTIONCONTAINERID_ADDONS;
            $popJSRuntimeManager->addPageSectionId($domain, $pssId, $moduleName, $psId, 'window-fullsize');
            $popJSRuntimeManager->addPageSectionId($domain, $pssId, $moduleName, $psId, 'window-maximize');
            $popJSRuntimeManager->addPageSectionId($domain, $pssId, $moduleName, $psId, 'window-minimize');
        }
    }
}

/**
 * Initialization
 */
if (!PoP_SSR_ServerUtils::disableServerSideRendering()) {
    $wassup_serverside_customfunctions = new Wassup_ServerSide_CustomFunctions();
    $popJSLibraryManager = PoP_ServerSide_LibrariesFactory::getJslibraryInstance();
    $popJSLibraryManager->register($wassup_serverside_customfunctions, array('addPageSectionIds'));
}
