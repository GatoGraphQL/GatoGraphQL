<?php

\PoP\Root\App::addAction('init', 'gfpopRemoveTinymce', 0);
function gfpopRemoveTinymce()
{
    if (!is_admin()) {
        // Remove the plugins from the tinyMCE
        \PoP\Root\App::removeFilter('tiny_mce_before_init', array( 'GFForms', 'modify_tiny_mce_4' ), 20);

        // Remove the addition of GF scripts in the front-end that we shall never need
        \PoP\Root\App::removeAction('wp_enqueue_scripts', array( 'RGForms', 'enqueueScripts' ), 11);
    }
}
