<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;
use PoP_Forms_ConfigurationUtils;
use PoP_FormUtils;
use PoP_Module_Processor_CaptchaFormInputs;
use PoP_Module_Processor_TextareaFormInputs;
use PoP_Module_Processor_TextFormInputs;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

abstract class AbstractEmailInviteMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getSuccessString(string|int $result_id): ?string
    {
        $emails = (array) $result_id;
        return sprintf(
            $this->getTranslationAPI()->__('Invitation sent to the following emails: <strong>%s</strong>'),
            implode(', ', $emails)
        );
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        // Get the list of all emails
        $emails = array();
        $form_emails = trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_EMAILS])->getValue([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_EMAILS]));
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

        $userTypeAPI = UserTypeAPIFacade::getInstance();
        if (PoP_FormUtils::useLoggedinuserData() && App::getState('is-user-logged-in')) {
            $user_id = App::getState('current-user-id');
            $sender_name = $userTypeAPI->getUserDisplayName($user_id);
            $sender_url = $userTypeAPI->getUserURL($user_id);
        } else {
            $sender_name = trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_SENDERNAME])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_SENDERNAME]));
            $user_id = $sender_url = '';
            if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
                $captcha = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::COMPONENT_FORMINPUT_CAPTCHA])->getValue([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::COMPONENT_FORMINPUT_CAPTCHA]);
            }
        }
        $additional_msg = trim($this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE])->getValue([PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_ADDITIONALMESSAGE]));
        
        $mutationData['emails'] = $emails;
        $mutationData['user_id'] = $user_id;
        $mutationData['sender-name'] = $sender_name;
        $mutationData['sender-url'] = $sender_url;
        $mutationData['additional-msg'] = $additional_msg;
        
        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $mutationData['captcha'] = $captcha;
        }
    }
}
