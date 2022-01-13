<?php

class PoP_ResourceLoader_SSR_HandlebarsHelpersHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'handlebars-helpers:enterModule:response',
            array($this, 'getEntermoduleResponse'),
            10,
            8
        );
    }

    public function getEntermoduleResponse($response, $context, $moduleName, $domain, $pssId, $psId, $bsId, $bId)
    {

        // Add the CSS style links in the body?
        if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {
            if ($resources = $context[GD_JS_RESOURCES]) {
                $popResourceLoader = PoP_ResourceLoader_ServerSide_LibrariesFactory::getResourceloaderInstance();
                $resourceTags = $popResourceLoader->includeResources($domain, $bId, $resources, true);
                $response = $resourceTags.$response;
            }
        }

        return $response;
    }
}

/**
 * Initialization
 */
new PoP_ResourceLoader_SSR_HandlebarsHelpersHooks();
