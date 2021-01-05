<?php

declare(strict_types=1);

namespace PoPSitesWassup\ShareMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class ShareByEmailMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        if (empty($form_data['name'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['email'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = TranslationAPIFacade::getInstance()->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($form_data['target-url'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('The shared-page URL cannot be empty.', 'pop-genericforms');
        }

        if (empty($form_data['target-title'])) {
            $errors[] = TranslationAPIFacade::getInstance()->__('The shared-page title cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($form_data)
    {
        HooksAPIFacade::getInstance()->doAction('pop_sharebyemail', $form_data);
    }

    protected function doExecute($form_data)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $subject = sprintf(
            TranslationAPIFacade::getInstance()->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                TranslationAPIFacade::getInstance()->__('%s is sharing with you: %s', 'pop-genericforms'),
                $form_data['name'],
                $form_data['target-title']
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                TranslationAPIFacade::getInstance()->__('%s is sharing with you: <a href="%s">%s</a>', 'pop-genericforms'),
                $form_data['name'],
                $form_data['target-url'],
                $form_data['target-title']
            )
        ) . ($form_data['message'] ? sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Additional message', 'pop-genericforms'),
            $form_data['message']
        ) : '');

        return \PoP_EmailSender_Utils::sendEmail($form_data['email'], $subject, $msg);
    }

    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $result = $this->doExecute($form_data);

        // Allow for additional operations
        $this->additionals($form_data);

        return $result;
    }
}
