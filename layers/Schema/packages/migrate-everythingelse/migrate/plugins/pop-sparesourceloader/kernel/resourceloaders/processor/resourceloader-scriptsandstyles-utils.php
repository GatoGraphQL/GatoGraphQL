<?php

class PoPWebPlatform_SPAResourceLoader_ScriptsAndStylesUtils
{
    public static function getLoadingframeResources($model_instance_id = null)
    {

        // To obtain the list of all resources that are always loaded, we can simply calculate the resources for this actual request,
        // for page /generate-theme/ (POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME), which because it doesn't add blocks or anything to the output,
        // it is strip of extra stuff, making it the minimum loading experience
        $js_loadingframe_resources_pack = PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::getResourcesPack(POP_RESOURCELOADER_RESOURCETYPE_JS, $model_instance_id);
        $css_loadingframe_resources_pack = PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils::getResourcesPack(POP_RESOURCELOADER_RESOURCETYPE_CSS, $model_instance_id);
        return array_merge(
            $js_loadingframe_resources_pack['resources']['all'],
            $css_loadingframe_resources_pack['resources']['all']
        );
    }
}
