<?php

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsLocationsMapLatlng');
function gdJqueryConstantsLocationsMapLatlng($jqueryConstants)
{
    $values = \PoP\Root\App::getHookManager()->applyFilters('gd_locationsmap_latlng', array());
    
    if (!empty($values)) {
        $jqueryConstants['LOCATIONSMAP_LAT'] = $values['lat'];
        $jqueryConstants['LOCATIONSMAP_LNG'] = $values['lng'];
        $jqueryConstants['LOCATIONSMAP_ZOOM'] = $values['zoom'];
        $jqueryConstants['LOCATIONSMAP_1MARKER_ZOOM'] = $values['1marker_zoom'];
    }
    
    return $jqueryConstants;
}
