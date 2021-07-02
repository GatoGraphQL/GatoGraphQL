<?php
use PoP\Hooks\Facades\HooksAPIFacade;

function gdSocialmediaProviderSettings()
{
    return HooksAPIFacade::getInstance()->applyFilters('gd_socialmedia:providers', array());
}

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsSocialmediaproviders');
function gdJqueryConstantsSocialmediaproviders($jqueryConstants)
{
    $jqueryConstants['SOCIALMEDIA'] = gdSocialmediaProviderSettings();
    return $jqueryConstants;
}
