<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoPSchema\Users\Constants\InputNames;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class AbstractUserUpdateUserMetaValueMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    public function validateErrors(array $form_data): ?array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
            $target_id = $form_data['target_id'];

            // Make sure the user exists
            $target = $cmsusersapi->getUserById($target_id);
            if (!$target) {
                $errors[] = TranslationAPIFacade::getInstance()->__('The requested user does not exist.', 'pop-coreprocessors');
            }
        }
        return $errors;
    }

    protected function getRequestKey()
    {
        return InputNames::USER_ID;
    }

    protected function additionals($target_id, $form_data)
    {
        HooksAPIFacade::getInstance()->doAction('gd_updateusermetavalue:user', $target_id, $form_data);
        parent::additionals($target_id, $form_data);
    }
}
