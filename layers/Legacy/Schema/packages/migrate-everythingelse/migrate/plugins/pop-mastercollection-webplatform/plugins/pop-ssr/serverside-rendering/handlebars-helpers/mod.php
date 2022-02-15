<?php
/**
 * Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_ModHelpers
{
    public function mod($lvalue, $rvalue, $options)
    {
        // Comment Leo: Not needed in PHP => Commented out
        // if (count($arguments) < 3) {
        //     throw new \PoP\Root\Exception\GenericException("Handlebars Helper equal needs 2 parameters");
        // }
            
        $offset = $options['hash']['offset'] ?? 0;
                
        if (($lvalue + $offset) % $rvalue === 0) {
            return $options['fn']();
        } else {
            return $options['inverse']();
        }
    }
}

/**
 * Initialization
 */
global $pop_serverside_modhelpers;
$pop_serverside_modhelpers = new PoP_ServerSide_ModHelpers();
