<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoP\EditUsers\FunctionAPIFactory;

class CreateUpdateUserMutationResolver extends AbstractMutationResolver
{
    protected function getRole()
    {
        return 'subscriber';
    }

    protected function validateContent(array &$errors, array $form_data): void
    {
        if (empty($form_data['first_name'])) {
            $errors[] = $this->getTranslationAPI()->__('The name cannot be empty', 'pop-application');
        }

        // Validate email
        $user_email = $form_data['user_email'];
        if ($user_email == '') {
            $errors[] = $this->getTranslationAPI()->__('The e-mail cannot be empty', 'pop-application');
        } elseif (! is_email($user_email)) {
            $errors[] = $this->getTranslationAPI()->__('The email address isn&#8217;t correct.', 'pop-application');
        }

        $limited_email_domains = get_site_option('limited_email_domains');
        if (is_array($limited_email_domains) && empty($limited_email_domains) == false) {
            $emaildomain = substr($user_email, 1 + strpos($user_email, '@'));
            if (in_array($emaildomain, $limited_email_domains) == false) {
                $errors[] = $this->getTranslationAPI()->__('That email address is not allowed!', 'pop-application');
            }
        }
    }

    protected function validateCreateContent(array &$errors, array $form_data): void
    {

        // Check the username
        $user_login = $form_data['username'];
        if ($user_login == '') {
            $errors[] = $this->getTranslationAPI()->__('The username cannot be empty.', 'pop-application');
        } elseif (! validate_username($user_login)) {
            $errors[] = $this->getTranslationAPI()->__('This username is invalid because it uses illegal characters. Please enter a valid username.', 'pop-application');
        } elseif (username_exists($user_login)) {
            $errors[] = $this->getTranslationAPI()->__('This username is already registered. Please choose another one.', 'pop-application');
        }

        // Check the e-mail address
        $user_email = $form_data['user_email'];
        if (email_exists($user_email)) {
            $errors[] = $this->getTranslationAPI()->__('This email is already registered, please choose another one.', 'pop-application');
        }

        // Validate Password
        $password = $form_data['password'];
        $repeatpassword =  $form_data['repeat_password'];

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

        // Validate the captcha
        if (\PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $captcha = $form_data['captcha'];

            $captcha_validation = GD_Captcha::validate($captcha);
            if (GeneralUtils::isError($captcha_validation)) {
                /** @var Error */
                $error = $captcha_validation;
                $errors[] = $error->getMessageOrCode();
            }
        }
    }

    protected function validateUpdateContent(array &$errors, array $form_data): void
    {
        $user_id = $form_data['user_id'];
        $user_email = $form_data['user_email'];

        $email_user_id = email_exists($user_email);
        if ($email_user_id && $email_user_id !== $user_id) {
            $errors[] = $this->getTranslationAPI()->__('That email address already exists in our system!', 'pop-application');
        }
    }

    protected function getUpdateuserData($form_data)
    {
        $user_data = array(
            'id' => $form_data['user_id'],
            'firstName' => $form_data['first_name'],
            'email' => $form_data['user_email'],
            'description' => $form_data['description'],
            'url' => $form_data['user_url']
        );

        return $user_data;
    }

    protected function getCreateuserData($form_data)
    {
        $user_data = $this->getUpdateuserData($form_data);

        // ID not needed
        unset($user_data['id']);

        // Assign the role only when creating a user
        $user_data['role'] = $this->getRole();

        // Add the password
        $user_data['password'] = $form_data['password'];

        // Username
        $user_data['login'] = $form_data['username'];

        return $user_data;
    }

    protected function executeUpdateuser($user_data)
    {
        $cmseditusersapi = FunctionAPIFactory::getInstance();
        return $cmseditusersapi->updateUser($user_data);
    }

    protected function createupdateuser($user_id, $form_data): void
    {
    }

    protected function updateuser($form_data)
    {
        $user_data = $this->getUpdateuserData($form_data);
        $user_id = $user_data['id'];

        $result = $this->executeUpdateuser($user_data);

        if (GeneralUtils::isError($result)) {
            return $result;
        }

        $this->createupdateuser($user_id, $form_data);

        return $user_id;
    }

    protected function executeCreateuser($user_data)
    {
        $cmseditusersapi = FunctionAPIFactory::getInstance();
        return $cmseditusersapi->insertUser($user_data);
    }

    protected function createuser($form_data)
    {
        $user_data = $this->getCreateuserData($form_data);
        $result = $this->executeCreateuser($user_data);

        if (GeneralUtils::isError($result)) {
            return $result;
        }

        $user_id = $result;

        $this->createupdateuser($user_id, $form_data);

        return $user_id;
    }

    public function executeMutation(array $form_data): mixed
    {
        // If user is logged in => It's Update
        // Otherwise => It's Create
        $vars = ApplicationState::getVars();
        if ($vars['is-user-logged-in']) {
            return $this->update($form_data);
        }

        return $this->create($form_data);
    }

    protected function additionals($user_id, $form_data): void
    {
        $this->getHooksAPI()->doAction('gd_createupdate_user:additionals', $user_id, $form_data);
    }
    protected function additionalsUpdate($user_id, $form_data): void
    {
        $this->getHooksAPI()->doAction('gd_createupdate_user:additionalsUpdate', $user_id, $form_data);
    }
    protected function additionalsCreate($user_id, $form_data): void
    {
        $this->getHooksAPI()->doAction('gd_createupdate_user:additionalsCreate', $user_id, $form_data);
    }

    public function validateErrors(array $form_data): array
    {
        $errors = [];
        $this->validateContent($errors, $form_data);
        $vars = ApplicationState::getVars();
        if ($vars['is-user-logged-in']) {
            $this->validateUpdateContent($errors, $form_data);
        } else {
            $this->validateCreateContent($errors, $form_data);
        }
        return $errors;
    }

    /**
     * @return mixed The ID of the updated entity, or an Error
     */
    protected function update(array $form_data): string | int | Error
    {
        // Do the Post update
        $user_id = $this->updateuser($form_data);
        if (GeneralUtils::isError($user_id)) {
            return $user_id;
        }

        // Allow for additional operations (eg: set Action categories)
        $this->additionalsUpdate($user_id, $form_data);
        $this->additionals($user_id, $form_data);

        // Trigger to update the display_name and nickname
        userNameUpdated($user_id);
        return $user_id;
    }

    /**
     * @return mixed The ID of the created entity, or an Error
     */
    protected function create(array $form_data): string | int | Error
    {
        $user_id = $this->createuser($form_data);
        if (GeneralUtils::isError($user_id)) {
            return $user_id;
        }

        // Allow for additional operations (eg: set Action categories)
        $this->additionalsCreate($user_id, $form_data);
        $this->additionals($user_id, $form_data);

        return $user_id;
        // Comment Leo 21/09/2015: we don't use this function anymore to send the notifications to the admin/user. Instead, use our own hooks.
        // Send notification of new user
        // wpNewUserNotification( $user_id, $form_data['password'] );
    }
}
