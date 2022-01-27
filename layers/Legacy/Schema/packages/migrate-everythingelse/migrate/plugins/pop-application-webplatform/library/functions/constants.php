<?php
use PoP\Engine\Constants\FormInputConstants;

define('POP_VALUEFORMAT_BOOLTOSTRING', 'bool-to-str');

\PoP\Root\App::addFilter('gd_jquery_constants', 'gdJqueryConstantsWebPlatformconstants');
function gdJqueryConstantsWebPlatformconstants($jqueryConstants)
{
    $jqueryConstants['BOOLSTRING_TRUE'] = FormInputConstants::BOOLSTRING_TRUE;
    $jqueryConstants['BOOLSTRING_FALSE'] = FormInputConstants::BOOLSTRING_FALSE;
    $jqueryConstants['VALUEFORMAT_BOOLTOSTRING'] = POP_VALUEFORMAT_BOOLTOSTRING;
    
    return $jqueryConstants;
}
