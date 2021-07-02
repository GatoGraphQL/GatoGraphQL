<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
*/
class PoP_ServerSide_OperatorsHelperCallers
{
    public static function eq($lvalue, $rvalue)
    {
        global $pop_serverside_operatorshelpers;
        return $pop_serverside_operatorshelpers->eq($lvalue, $rvalue);
    }

    public static function and($lvalue, $rvalue)
    {
        global $pop_serverside_operatorshelpers;
        return $pop_serverside_operatorshelpers->and($lvalue, $rvalue);
    }

    public static function or($lvalue, $rvalue)
    {
        global $pop_serverside_operatorshelpers;
        return $pop_serverside_operatorshelpers->or($lvalue, $rvalue);
    }

    public static function in($lvalue, $rvalue)
    {
        global $pop_serverside_operatorshelpers;
        return $pop_serverside_operatorshelpers->in($lvalue, $rvalue);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_OperatorsHelperCallers::class);
