<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Templates functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter( 'template_include', 'var_template_include', 1000 );
function var_template_include( $t ){
    $GLOBALS['current_theme_template'] = basename($t);
    return $t;
}
function get_current_template() {

    if( !isset( $GLOBALS['current_theme_template'] ) )
        return false;
   
    return $GLOBALS['current_theme_template'];
}
