<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Remove the <h2>Bookings</h2> at the bottom of each event
 */

if (!is_admin()) {
    //override single page with formats?
    HooksAPIFacade::getInstance()->removeFilter('the_content', array('EM_Event_Post','the_content'));
}
