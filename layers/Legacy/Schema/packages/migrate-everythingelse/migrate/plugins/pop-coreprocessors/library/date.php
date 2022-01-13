<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsDaterangeImpl');
function gdJqueryConstantsDaterangeImpl($jqueryConstants)
{
    $jqueryConstants['DATERANGE_SEPARATOR'] = ' - ';

    /*
    $jqueryConstants['DATERANGE_TODAY'] = TranslationAPIFacade::getInstance()->__('Today', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_THISMONTH'] = TranslationAPIFacade::getInstance()->__('This Month', 'pop-coreprocessors');

    $jqueryConstants['DATERANGE_YESTERDAY'] = TranslationAPIFacade::getInstance()->__('Yesterday', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_LAST7DAYS'] = TranslationAPIFacade::getInstance()->__('Last 7 Days', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_LAST30DAYS'] = TranslationAPIFacade::getInstance()->__('Last 30 Days', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_LASTMONTH'] = TranslationAPIFacade::getInstance()->__('Last Month', 'pop-coreprocessors');

    $jqueryConstants['DATERANGE_TOMORROW'] = TranslationAPIFacade::getInstance()->__('Tomorrow', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_NEXT7DAYS'] = TranslationAPIFacade::getInstance()->__('Next 7 Days', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_NEXT30DAYS'] = TranslationAPIFacade::getInstance()->__('Next 30 Days', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_NEXTMONTH'] = TranslationAPIFacade::getInstance()->__('Next Month', 'pop-coreprocessors');
    */
    $jqueryConstants['DATERANGE_APPLY'] = TranslationAPIFacade::getInstance()->__('Apply', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_CANCEL'] = TranslationAPIFacade::getInstance()->__('Cancel', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_FROM'] = TranslationAPIFacade::getInstance()->__('From', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_TO'] = TranslationAPIFacade::getInstance()->__('To', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_CUSTOMRANGE'] = TranslationAPIFacade::getInstance()->__('Custom Range', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_FORMAT'] = TranslationAPIFacade::getInstance()->__('DD/MM/YYYY', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_TIMEFORMAT'] = TranslationAPIFacade::getInstance()->__('h:mm A', 'pop-coreprocessors');
    $jqueryConstants['DATERANGE_DAYSOFWEEK'] = array(
        TranslationAPIFacade::getInstance()->__('Su', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('Mo', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('Tu', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('We', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('Th', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('Fr', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('Sa', 'pop-coreprocessors'),
    );
    $jqueryConstants['DATERANGE_MONTHNAMES'] = array(
        TranslationAPIFacade::getInstance()->__('January', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('February', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('March', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('April', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('May', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('June', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('July', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('August', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('September', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('October', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('November', 'pop-coreprocessors'),
        TranslationAPIFacade::getInstance()->__('December', 'pop-coreprocessors'),
    );

    return $jqueryConstants;
}
