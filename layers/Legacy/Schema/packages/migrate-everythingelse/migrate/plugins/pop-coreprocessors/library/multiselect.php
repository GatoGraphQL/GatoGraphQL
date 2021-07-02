<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsMultiselectImpl');
function gdJqueryConstantsMultiselectImpl($jqueryConstants)
{
    $jqueryConstants['MULTISELECT_NONSELECTEDTEXT'] = TranslationAPIFacade::getInstance()->__('None selected', 'pop-coreprocessors');
    $jqueryConstants['MULTISELECT_NSELECTEDTEXT'] = TranslationAPIFacade::getInstance()->__('selected', 'pop-coreprocessors');
    $jqueryConstants['MULTISELECT_ALLSELECTEDTEXT'] = TranslationAPIFacade::getInstance()->__('All selected', 'pop-coreprocessors');
    
    return $jqueryConstants;
}
