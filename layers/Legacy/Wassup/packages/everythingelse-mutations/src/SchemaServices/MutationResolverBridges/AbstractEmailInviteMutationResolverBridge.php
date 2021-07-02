<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

abstract class AbstractEmailInviteMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getSuccessString(string | int $result_id): ?string
    {
        $emails = (array) $result_id;
        return sprintf(
            $this->translationAPI->__('Invitation sent to the following emails: <strong>%s</strong>'),
            implode(', ', $emails)
        );
    }

    public function getFormData(): array
    {
        // Get the list of all emails
        $emails = array();
        $form_emails = trim($this->moduleProcessorManager->getProcessor([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_EMAILS])->getValue([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_EMAILS]));
        // Remove newlines
        $form_emails = trim(preg_replace('/\s+/', ' ', $form_emails));
        if ($form_emails) {
            foreach (multiexplode(array(',', ';', ' '), $form_emails) as $email) {
                // Remove white spaces
                $email = trim($email);
                if ($email) {
                    $emails[] = $email;
                }
            }
        }

        $vars = ApplicationState::getVars();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        if (PoP_FormUtils::useLoggedinuserData() && $vars['global-userstate']['is-user-logged-in']) {
            $user_id = $vars['global-userstate']['current-user-id'];
            $sender_name = $cmsengineapi->getUserDisplayName($user_id);
            $sender_url = $cmsengineapi->getUserURL($user_id);
        } else {
            $sender_name = trim($this->moduleProcessorManager->getProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SENDERNAME])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SENDERNAME]));
            $user_id = $sender_url = '';
            if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                $captcha = $this->moduleProcessorManager->getProcessor([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA])->getValue([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA]);
            }
        }
        $additional_msg = trim($this->moduleProcessorManager->getProcessor([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_ADDITIONALMESSAGE])->getValue([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_ADDITIONALMESSAGE]));
        $form_data = array(
            'emails' => $emails,
            'user_id' => $user_id,
            'sender-name' => $sender_name,
            'sender-url' => $sender_url,
            'additional-msg' => $additional_msg,
        );
        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $form_data['captcha'] = $captcha;
        }

        return $form_data;
    }
}
