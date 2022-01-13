<?php

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsWslImpl');
function gdJqueryConstantsWslImpl($jqueryConstants)
{
    $jqueryConstants['SOCIALLOGIN_LOGINUSER_CLOSETIME'] = \PoP\Root\App::getHookManager()->applyFilters('sociallogin:loginuser:closetime', 1500);
    
    return $jqueryConstants;
}
