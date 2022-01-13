<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\App;
use PoPSchema\Users\Constants\InputNames;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

class AbstractUserUpdateUserMetaValueMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
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
                $errors[] = $this->__('The requested user does not exist.', 'pop-coreprocessors');
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
        App::doAction('gd_updateusermetavalue:user', $target_id, $form_data);
        parent::additionals($target_id, $form_data);
    }
}
