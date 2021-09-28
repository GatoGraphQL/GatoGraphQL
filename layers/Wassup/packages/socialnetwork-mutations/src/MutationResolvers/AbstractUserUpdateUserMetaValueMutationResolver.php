<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Users\Constants\InputNames;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

class AbstractUserUpdateUserMetaValueMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    protected UserTypeAPIInterface $userTypeAPI;

    #[Required]
    public function autowireAbstractUserUpdateUserMetaValueMutationResolver(
        UserTypeAPIInterface $userTypeAPI,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
    }

    public function validateErrors(array $form_data): ?array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $target_id = $form_data['target_id'];

            // Make sure the user exists
            $target = $this->userTypeAPI->getUserById($target_id);
            if (!$target) {
                $errors[] = $this->translationAPI->__('The requested user does not exist.', 'pop-coreprocessors');
            }
        }
        return $errors;
    }

    protected function getRequestKey()
    {
        return InputNames::USER_ID;
    }

    protected function additionals($target_id, $form_data): void
    {
        $this->hooksAPI->doAction('gd_updateusermetavalue:user', $target_id, $form_data);
        parent::additionals($target_id, $form_data);
    }
}
