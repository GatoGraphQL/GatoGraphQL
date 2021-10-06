<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserState\State\ApplicationStateUtils;
use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class LogoutMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    protected UserStateTypeMutationAPIInterface $userStateTypeMutationAPI;

    #[Required]
    final public function autowireLogoutMutationResolver(
        UserStateTypeMutationAPIInterface $userStateTypeMutationAPI,
    ): void {
        $this->userStateTypeMutationAPI = $userStateTypeMutationAPI;
    }

    public function validateErrors(array $form_data): array
    {
        $errors = [];
        $this->validateUserIsLoggedIn($errors);
        return $errors;
    }
    public function executeMutation(array $form_data): mixed
    {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['current-user-id'];

        $this->userStateTypeMutationAPI->logout();

        // Modify the routing-state with the newly logged in user info
        ApplicationStateUtils::setUserStateVars(ApplicationState::$vars);

        $this->hooksAPI->doAction('gd:user:loggedout', $user_id);
        return $user_id;
    }
}
