<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\UserAccount\FunctionAPIFactory;

class ChangeUserPasswordMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(array $form_data): array
    {
        $errors = [];
        $cmsuseraccountapi = FunctionAPIFactory::getInstance();
        // Validate Password
        // Check current password really belongs to the user
        $current_password = $form_data['current_password'];
        $password = $form_data['password'];
        $repeatpassword =  $form_data['repeat_password'];

        if (!$current_password) {
            $errors[] = $this->getTranslationAPI()->__('Please provide the current password.', 'pop-application');
        } elseif (!$cmsuseraccountapi->checkPassword($form_data['user_id'], $current_password)) {
            $errors[] = $this->getTranslationAPI()->__('Current password is incorrect.', 'pop-application');
        }

        if (!$password) {
            $errors[] = $this->getTranslationAPI()->__('The password cannot be emtpy.', 'pop-application');
        } elseif (strlen($password) < 8) {
            $errors[] = $this->getTranslationAPI()->__('The password must be at least 8 characters long.', 'pop-application');
        } else {
            if (!$repeatpassword) {
                $errors[] = $this->getTranslationAPI()->__('Please confirm the password.', 'pop-application');
            } elseif ($password !== $repeatpassword) {
                $errors[] = $this->getTranslationAPI()->__('Passwords do not match.', 'pop-application');
            }
        }
        return $errors;
    }

    protected function executeChangepassword($user_data)
    {
        $cmseditusersapi = \PoP\EditUsers\FunctionAPIFactory::getInstance();
        return $cmseditusersapi->updateUser($user_data);
    }

    protected function getChangepasswordData($form_data)
    {
        $user_data = array(
            'id' => $form_data['user_id'],
            'password' => $form_data['password']
        );

        return $user_data;
    }

    public function executeMutation(array $form_data): mixed
    {
        $user_data = $this->getChangepasswordData($form_data);
        $result = $this->executeChangepassword($user_data);

        if (GeneralUtils::isError($result)) {
            return $result;
        }

        $user_id = $user_data['ID'];

        \PoP\Root\App::getHookManager()->doAction('gd_changepassword_user', $user_id, $form_data);

        return $user_id;
    }
}
