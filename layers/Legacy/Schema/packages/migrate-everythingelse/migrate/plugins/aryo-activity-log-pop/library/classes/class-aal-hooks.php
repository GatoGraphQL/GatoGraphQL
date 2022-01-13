<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AAL_PoP_Hooks
{
    public function __construct()
    {

        // Hook in all the notification hooks
        \PoP\Root\App::doAction('AAL_PoP_Hooks');
    }
}
