<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('wp_mail', 'popEmailsenderDecodeSubject');
function popEmailsenderDecodeSubject($props)
{

    //decode entities, but run kses first just in case users use placeholders containing html
    $props['subject'] = html_entity_decode(wp_kses_data($props['subject']));
    return $props;
}
