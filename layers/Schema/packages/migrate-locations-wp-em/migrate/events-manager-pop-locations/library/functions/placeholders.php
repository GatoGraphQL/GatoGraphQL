<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// Override the Location Balloon: if no address, don't show it
HooksAPIFacade::getInstance()->addFilter('em_location_output_placeholder', 'gdEmLocationOutputPlaceholderNoaddress', 10, 4);
function gdEmLocationOutputPlaceholderNoaddress($result, $location, $placeholder, $target = 'html')
{
    switch ($placeholder) {
        case '#_LOCATIONFULLLINE':
        case '#_LOCATIONFULLBR':
            $results = array();
            if (!empty($location->location_address)) {
                $results[] = $location->location_address;
            }
            if (!empty($location->location_town)) {
                $results[] = $location->location_town;
            }
            if (!empty($location->location_state)) {
                $results[] = $location->location_state;
            }
            if (!empty($location->location_postcode)) {
                $results[] = $location->location_postcode;
            }
            if (!empty($location->location_region)) {
                $results[] = $location->location_region;
            }
            if ($placeholder == '#_LOCATIONFULLLINE') {
                $sep = ', ';
            } else {
                $sep = '<br/>';
            }
            $result = implode($sep, $results);
            break;
    }

    return $result;
}
