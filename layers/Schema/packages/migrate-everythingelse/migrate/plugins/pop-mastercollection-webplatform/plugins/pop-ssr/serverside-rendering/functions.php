<?php
class PoP_Core_ServerSide_Functions
{

    //-------------------------------------------------
    // PUBLIC FUNCTIONS
    //-------------------------------------------------

    public function expandJSKeys(&$args)
    {
        $context = &$args['context'];

        if ($context[GD_JS_FONTAWESOME]) {
            $context['fontawesome'] = $context[GD_JS_FONTAWESOME];
        }
    }
}

/**
 * Initialization
 */
if (!PoP_SSR_ServerUtils::disableServerSideRendering()) {
    $pop_core_serverside_functions = new PoP_Core_ServerSide_Functions();
    $popJSLibraryManager = PoP_ServerSide_LibrariesFactory::getJslibraryInstance();
    $popJSLibraryManager->register($pop_core_serverside_functions, array('expandJSKeys'));
}
