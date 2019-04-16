<?php
namespace PoP\Engine;

class APIUtils
{
    public static function getEndpoint($url, $dataoutputitems = null)
    {
        $cmshelpers = \PoP\CMS\HelperAPI_Factory::getInstance();
        $dataoutputitems = $dataoutputitems ?? [
            GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
            GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
            GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
        ];
        $endpoint = $cmshelpers->addQueryArgs([
            GD_URLPARAM_ACTION => POP_ACTION_API,
            GD_URLPARAM_OUTPUT => GD_URLPARAM_OUTPUT_JSON,
            GD_URLPARAM_DATAOUTPUTMODE => GD_URLPARAM_DATAOUTPUTMODE_COMBINED,
            // GD_URLPARAM_DATABASESOUTPUTMODE => GD_URLPARAM_DATABASESOUTPUTMODE_COMBINED,
            GD_URLPARAM_DATAOUTPUTITEMS => implode(
                POP_CONSTANT_PARAMVALUE_SEPARATOR,
                $dataoutputitems
            ),
        ], $url);

        if ($mangled = $_REQUEST[GD_URLPARAM_MANGLED]) {
            $endpoint = $cmshelpers->addQueryArgs([
                GD_URLPARAM_MANGLED => $mangled,
            ], $endpoint);
        }

        return $endpoint;
    }
}
