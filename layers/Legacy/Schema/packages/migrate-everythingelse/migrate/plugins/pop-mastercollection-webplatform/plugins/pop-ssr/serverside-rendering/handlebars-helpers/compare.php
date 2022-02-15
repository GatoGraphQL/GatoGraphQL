<?php
/**
 * Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_CompareHelpers
{
    public function compare($lvalue, $rvalue, $options)
    {

        // Comment Leo: Not needed in PHP => Commented out
        // if (count($arguments) < 3) {
        //     throw new \PoP\Root\Exception\GenericException("Handlerbars Helper 'compare' needs 2 parameters");
        // }

        $operator = $options['hash']['operator'] ?? "==";

        $operators = array(
            'eq' =>       function ($l, $r) {
                return $l == $r;
            },
            '==' =>       function ($l, $r) {
                return $l == $r;
            },
            '===' =>      function ($l, $r) {
                return $l === $r;
            },
            '!=' =>       function ($l, $r) {
                return $l != $r;
            },
            '<' =>        function ($l, $r) {
                return $l < $r;
            },
            '>' =>        function ($l, $r) {
                return $l > $r;
            },
            '<=' =>       function ($l, $r) {
                return $l <= $r;
            },
            '>=' =>       function ($l, $r) {
                return $l >= $r;
            },
            'typeof' =>   function ($l, $r) {
                return gettype($l) == $r;
            },
            'in' =>       function ($l, $r) {
                return $r && array_search($l, $r) !== false;
            },
            'notin' =>       function ($l, $r) {
                return !$r || array_search($l, $r) === false;
            }
        );

        if (!$operators[$operator]) {
            throw new \PoP\Root\Exception\GenericException("Handlerbars Helper 'compare' doesn't know the operator ".$operator);
        }

        $result = $operators[$operator]($lvalue, $rvalue);

        if ($result) {
            return $options['fn']();
        } else {
            return $options['inverse']();
        }
    }
}

/**
 * Initialization
 */
global $pop_serverside_comparehelpers;
$pop_serverside_comparehelpers = new PoP_ServerSide_CompareHelpers();
