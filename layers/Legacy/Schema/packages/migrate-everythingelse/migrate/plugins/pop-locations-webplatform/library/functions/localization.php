<?php

\PoP\Root\App::addFilter('gd_jquery_constants', gdJqueryConstantsLocations(...));
function gdJqueryConstantsLocations($jqueryConstants)
{
    $jqueryConstants['LOCATIONSID_FIELDNAME'] = POP_INPUTNAME_LOCATIONID;
    return $jqueryConstants;
}
