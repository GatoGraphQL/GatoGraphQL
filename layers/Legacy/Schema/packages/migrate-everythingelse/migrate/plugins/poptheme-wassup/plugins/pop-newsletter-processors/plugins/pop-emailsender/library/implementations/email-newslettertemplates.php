<?php
define('POP_EMAILFRAME_NEWSLETTER', 'newsletter');
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_EmailSender_Templates_Newsletter extends PoP_EmailSender_Templates_Simple
{
    public function getName(): string
    {
        return POP_EMAILFRAME_NEWSLETTER;
    }

    public function getEmailframeBeforefooter(/*$frame, */$title, $emails, $names, $template)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        // Generate the URL to unsubscribe the first (which should be the only one!) email
        $email = $emails[0];
        $verificationcode = PoP_GenericForms_NewsletterUtils::getEmailVerificationcode($email);
        $url = RouteUtils::getRouteURL(POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION);

        $moduleprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $email_input_name = $moduleprocessor_manager->getProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL])->getName([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL]);
        $verification_input_name = $moduleprocessor_manager->getProcessor([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE])->getName([PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONCODE]);
        $url = GeneralUtils::addQueryArgs([
            $email_input_name => $email, 
            $verification_input_name => $verificationcode, 
        ], $url);
        
        // Add the link to unsubscribe
        return sprintf(
            '<p><small>%s</small></p>',
            sprintf(
                TranslationAPIFacade::getInstance()->__('<a href="%s">Unsubscribe</a> from this newsletter.', 'pop-emailsender'),
                $url
            )
        );
    }
}

/**
 * Initialization
 */
new PoP_EmailSender_Templates_Newsletter();
