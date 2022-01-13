<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdSocialmediaProviderSettings()
{
    return \PoP\Root\App::getHookManager()->applyFilters('gd_socialmedia:providers', array());
}

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsSocialmediaproviders');
function gdJqueryConstantsSocialmediaproviders($jqueryConstants)
{
    $jqueryConstants['SOCIALMEDIA'] = gdSocialmediaProviderSettings();
    return $jqueryConstants;
}
