<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// Execute last: remove the templates folder since the frame is added in the Lambda function instead
// HooksAPIFacade::getInstance()->addAction('sendemailToUsers:template_folder', '__return_false', PHP_INT_MAX);

// Replace the templates by removing the frame, adding only the Header and Footer
HooksAPIFacade::getInstance()->addAction('sendemailToUsers:template_folders', 'popMailerTemplates', PHP_INT_MAX);
function popMailerTemplates($template_folders)
{
    array_unshift($template_folders, POP_EMAILSENDER_AWS_DIR_RESOURCES.'/email-templates/default/');
    return $template_folders;
}
