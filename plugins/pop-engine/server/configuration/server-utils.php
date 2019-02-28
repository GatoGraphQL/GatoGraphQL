<?php
namespace PoP\Engine\Server;

class Utils
{
    protected static $override_configuration;

    public static function init()
    {

        // Allow to override the configuration with values passed in the query string:
        // "config": comma-separated string with all fields with value "true"
        // Whatever fields are not there, will be considered "false"
        self::$override_configuration = array();
        if (self::enableConfigByParams()) {
            self::$override_configuration = $_REQUEST[POP_URLPARAM_CONFIG] ? explode(POP_CONSTANT_PARAMVALUE_SEPARATOR, $_REQUEST[POP_URLPARAM_CONFIG]) : array();
        }
    }

    public static function doingOverrideConfiguration()
    {
        return !empty(self::$override_configuration);
    }

    public static function getOverrideConfiguration($key)
    {

        // If no values where defined in the configuration, then skip it completely
        if (empty(self::$override_configuration)) {
            return null;
        }

        // Check if the key has been given value "true"
        if (in_array($key, self::$override_configuration)) {
            return true;
        }

        // Otherwise, it has value "false"
        return false;
    }

    public static function isMangled()
    {

        // By default, it is mangled, if not mangled then param "mangled" must have value "none"
        // Coment Leo 13/01/2017: getVars() can't function properly since it references objects which have not been initialized yet,
        // when called at the very beginning. So then access the request directly
        return !$_REQUEST[GD_URLPARAM_MANGLED] || $_REQUEST[GD_URLPARAM_MANGLED] != GD_URLPARAM_MANGLED_NONE;
    }

    public static function enableConfigByParams()
    {
        if (defined('POP_SERVER_ENABLECONFIGBYPARAMS')) {
            return POP_SERVER_ENABLECONFIGBYPARAMS;
        }

        return false;
    }

    public static function failIfModulesDefinedTwice()
    {
        if (defined('POP_SERVER_FAILIFMODULESDEFINEDTWICE')) {
            return POP_SERVER_FAILIFMODULESDEFINEDTWICE;
        }

        return false;
    }

    public static function failIfSubcomponentDataloaderUndefined()
    {
        if (defined('POP_SERVER_FAILIFSUBCOMPONENTDATALOADERUNDEFINED')) {
            return POP_SERVER_FAILIFSUBCOMPONENTDATALOADERUNDEFINED;
        }

        return false;
    }

    public static function enableExtraurisByParams()
    {
        if (defined('POP_SERVER_ENABLEEXTRAURISBYPARAMS')) {
            return POP_SERVER_ENABLEEXTRAURISBYPARAMS;
        }

        return false;
    }

    /**
     * Use 'modules' or 'm' in the JS context. Used to compress the file size in PROD
     */
    public static function compactResponseJsonKeys()
    {

        // Do not compact if not mangled
        if (!self::isMangled()) {
            return false;
        }

        if (defined('POP_SERVER_COMPACTRESPONSEJSONKEYS')) {
            return POP_SERVER_COMPACTRESPONSEJSONKEYS;
        }

        return false;
    }

    public static function useCache()
    {

        // If we are overriding the configuration, then do NOT use the cache
        // Otherwise, parameters from the config have need to be added to $vars, however they can't,
        // since we want the $vars model_instance_id to not change when testing with the "config" param
        if (self::doingOverrideConfiguration()) {
            return false;
        }

        if (defined('POP_SERVER_USECACHE')) {
            return POP_SERVER_USECACHE;
        }

        return false;
    }

    public static function enableApi()
    {
        if (defined('POP_SERVER_ENABLEAPI')) {
            return POP_SERVER_ENABLEAPI;
        }

        return false;
    }
}

/**
 * Initialization
 */
Utils::init();
