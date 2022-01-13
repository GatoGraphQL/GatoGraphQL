<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AAL_PoP_Hooks
{
    public function __construct()
    {

        // Hook in all the notification hooks
        HooksAPIFacade::getInstance()->doAction('AAL_PoP_Hooks');
    }
}
