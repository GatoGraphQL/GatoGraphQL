<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('PoP_Module_Processor_CreateUserFormMesageFeedbackLayoutsBase:success:msg', 'popthemeWassupCreateuserSuccessmsg');
function popthemeWassupCreateuserSuccessmsg($msg)
{
    $emails = array();
    if (defined('POP_EMAILSENDER_INITIALIZED')) {
        $emails[] = PoP_EmailSender_Utils::getFromEmail();
    }
    if (defined('POP_NEWSLETTER_INITIALIZED')) {
        $emails[] = PoP_Newsletter_EmailUtils::getNewsletterEmail();
    }

    if ($emails) {
        $msg = sprintf(
            '<p>%s</p><ul>%s</ul>',
            TranslationAPIFacade::getInstance()->__('Please add the following emails to your contact list, to make sure you receive our notifications:', 'poptheme-wassup'),
            '<li>'.implode('</li><li>', $emails).'</li>'
        ).$msg;
    }

    return $msg;
}
