<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// Instead of using true/false values, use representative string, so it can be operated through #compare as a string (otherwise, true != 'true')
// Watch out! FALSE cannot be "", because then it's not sent as a value (it's filtered out when submitting a form when doing blockQueryState.filter = filter.find('.' + M.FORM_INPUT).filter(function () {return $.trim(this.value);}).serialize();)
// Watch out! They also cannot be true => "1" and false "0" because then it doens't send the keys in the array, then we have [Yes, No] with the values actually inverted!
define('POP_BOOLSTRING_TRUE', 'true');
define('POP_BOOLSTRING_FALSE', 'false');
define('POP_VALUEFORMAT_BOOLTOSTRING', 'bool-to-str');

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsWebPlatformconstants');
function gdJqueryConstantsWebPlatformconstants($jqueryConstants)
{
    $jqueryConstants['BOOLSTRING_TRUE'] = POP_BOOLSTRING_TRUE;
    $jqueryConstants['BOOLSTRING_FALSE'] = POP_BOOLSTRING_FALSE;
    $jqueryConstants['VALUEFORMAT_BOOLTOSTRING'] = POP_VALUEFORMAT_BOOLTOSTRING;
    
    return $jqueryConstants;
}
