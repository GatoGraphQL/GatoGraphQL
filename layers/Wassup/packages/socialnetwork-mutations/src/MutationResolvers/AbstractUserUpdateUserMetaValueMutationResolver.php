<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoPSchema\Users\Constants\InputNames;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class AbstractUserUpdateUserMetaValueMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    public function validateErrors(array $form_data): array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $target_id = $form_data['target_id'];

            // Make sure the user exists
            $target = $this->getUserTypeAPI()->getUserById($target_id);
            if (!$target) {
                $errors[] = $this->getTranslationAPI()->__('The requested user does not exist.', 'pop-coreprocessors');
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
        $this->getHooksAPI()->doAction('gd_updateusermetavalue:user', $target_id, $form_data);
        parent::additionals($target_id, $form_data);
    }
}
