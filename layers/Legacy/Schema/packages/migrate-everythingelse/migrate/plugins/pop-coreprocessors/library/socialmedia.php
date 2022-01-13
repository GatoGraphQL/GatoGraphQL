<?php

function gdSocialmediaProviderSettings()
{
    return \PoP\Root\App::applyFilters('gd_socialmedia:providers', array());
}

\PoP\Root\App::addFilter('gd_jquery_constants', 'gdJqueryConstantsSocialmediaproviders');
function gdJqueryConstantsSocialmediaproviders($jqueryConstants)
{
    $jqueryConstants['SOCIALMEDIA'] = gdSocialmediaProviderSettings();
    return $jqueryConstants;
}
