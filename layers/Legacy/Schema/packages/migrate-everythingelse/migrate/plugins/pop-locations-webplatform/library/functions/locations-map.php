<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsLocationsMapLatlng');
function gdJqueryConstantsLocationsMapLatlng($jqueryConstants)
{
    $values = HooksAPIFacade::getInstance()->applyFilters('gd_locationsmap_latlng', array());
    
    if (!empty($values)) {
        $jqueryConstants['LOCATIONSMAP_LAT'] = $values['lat'];
        $jqueryConstants['LOCATIONSMAP_LNG'] = $values['lng'];
        $jqueryConstants['LOCATIONSMAP_ZOOM'] = $values['zoom'];
        $jqueryConstants['LOCATIONSMAP_1MARKER_ZOOM'] = $values['1marker_zoom'];
    }
    
    return $jqueryConstants;
}
