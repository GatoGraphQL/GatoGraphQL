<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Do not validate the location address (a city alone is also valid)
 * Do validate Lat and Lng
 */
HooksAPIFacade::getInstance()->addAction('em_location', 'gdEmLocation', 10, 1);
function gdEmLocation($EM_Location)
{
    unset($EM_Location->required_fields['location_address']);
}
HooksAPIFacade::getInstance()->addFilter('em_location_validate', 'gdEmValidateLatlng', 10, 2);
function gdEmValidateLatlng($valid, $EM_Location)
{
    
    // No need to ask for both lat and lng, one of them is already enough (they are both full or empty)
    // Check only if the validation is true (which means, it does have an address)
    if ($valid && empty($EM_Location->location_latitude)) {
        $valid = false;
        $EM_Location->add_error(TranslationAPIFacade::getInstance()->__('The address was not found on the map', 'poptheme-wassup'));
    }

    return $valid;
}
