<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsWslImpl');
function gdJqueryConstantsWslImpl($jqueryConstants)
{
    $jqueryConstants['SOCIALLOGIN_LOGINUSER_CLOSETIME'] = HooksAPIFacade::getInstance()->applyFilters('sociallogin:loginuser:closetime', 1500);
    
    return $jqueryConstants;
}
