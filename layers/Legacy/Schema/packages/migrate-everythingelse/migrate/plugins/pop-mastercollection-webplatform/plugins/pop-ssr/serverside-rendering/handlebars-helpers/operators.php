<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_OperatorsHelpers
{
    public function eq($lvalue, $rvalue)
    {
        return $lvalue === $rvalue;
    }

    public function and($lvalue, $rvalue)
    {
        return $lvalue && $rvalue;
    }

    public function or($lvalue, $rvalue)
    {
        return $lvalue || $rvalue;
    }

    public function in($lvalue, $rvalue)
    {
        return in_array($lvalue, ($rvalue ?? array()));
    }
}

/**
 * Initialization
 */
global $pop_serverside_operatorshelpers;
$pop_serverside_operatorshelpers = new PoP_ServerSide_OperatorsHelpers();
