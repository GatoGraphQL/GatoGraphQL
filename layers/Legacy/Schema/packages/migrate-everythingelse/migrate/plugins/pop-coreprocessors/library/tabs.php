<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

define('POP_IDS_TABS_REMEMBERCHECKBOX', 'tabs-remember');

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popcoreTabsJqueryConstants');
function popcoreTabsJqueryConstants($jqueryConstants)
{
    $opentabs = HooksAPIFacade::getInstance()->applyFilters(
        'popcoreTabsJqueryConstants:opentabs',
        true
    );
    $jqueryConstants['OPENTABS'] = $opentabs ? true : "";

    $jqueryConstants['IDS_TABS_REMEMBERCHECKBOX'] = POP_IDS_TABS_REMEMBERCHECKBOX;

    // Re-open tabs? Add 'data-dismiss="alert"' so that it always closes the alert, either pressing accept or cancel
    $msg_placeholder = '<div class="pop-notificationmsg %s alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" aria-hidden="true" data-dismiss="alert">×</button>%s</div>';
    $btn_placeholder = '<button type="button" class="btn btn-default" aria-hidden="true" data-dismiss="alert" %s>%s</button>';
    $btns =
    '<div class="btn-group btn-group-xs">'.
    sprintf(
        $btn_placeholder,
        'onclick="{0}"',
        TranslationAPIFacade::getInstance()->__('Accept', 'pop-coreprocessors')
    ).
    sprintf(
        $btn_placeholder,
        'onclick="{1}"',
        TranslationAPIFacade::getInstance()->__('Cancel', 'pop-coreprocessors')
    ).
    '</div>';
    $checkbox = sprintf(
        '<div class="checkbox">'.
        '<label>'.
                '<input type="checkbox" id="%s">%s'.
        '</label>'.
        '</div>',
        POP_IDS_TABS_REMEMBERCHECKBOX,
        TranslationAPIFacade::getInstance()->__('Remember', 'pop-coreprocessors')
    );

    $formgroup_placeholder = '%s';//'<div class="form-group">%s</div>';
    $message = sprintf(
        $msg_placeholder,
        'website-level sessiontabs',
        sprintf(
            '%s%s%s', //'<div class="form-inline">%s%s%s</div>',
            sprintf(
                $formgroup_placeholder,
                TranslationAPIFacade::getInstance()->__('Reopen previous session tabs?', 'pop-coreprocessors')
            ),
            sprintf(
                $formgroup_placeholder,
                $btns
            ),
            sprintf(
                $formgroup_placeholder,
                $checkbox
            )
        )
    );
    $jqueryConstants['TABS_REOPENMSG'] = HooksAPIFacade::getInstance()->applyFilters('pop_sw_message:reopentabs', $message);

    return $jqueryConstants;
}
