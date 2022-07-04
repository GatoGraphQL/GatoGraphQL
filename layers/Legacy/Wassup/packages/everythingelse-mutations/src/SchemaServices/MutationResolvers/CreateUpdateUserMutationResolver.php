<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Forms_ConfigurationUtils;
use GD_Captcha;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\EditUsers\FunctionAPIFactory;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Exception\GenericClientException;

class CreateUpdateUserMutationResolver extends AbstractMutationResolver
{
    protected function getRole()
    {
        return 'subscriber';
    }

    protected function validateContent(array &$errors, FieldDataProviderInterface $fieldDataProvider): void
    {
        if (empty($fieldDataProvider->get('first_name'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('The name cannot be empty', 'pop-application');
        }

        // Validate email
        $user_email = $fieldDataProvider->get('user_email');
        if ($user_email === '') {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('The e-mail cannot be empty', 'pop-application');
        } elseif (! is_email($user_email)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('The email address isn&#8217;t correct.', 'pop-application');
        }

        $limited_email_domains = get_site_option('limited_email_domains');
        if (is_array($limited_email_domains) && empty($limited_email_domains) == false) {
            $emaildomain = substr($user_email, 1 + strpos($user_email, '@'));
            if (in_array($emaildomain, $limited_email_domains) == false) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->getTranslationAPI()->__('That email address is not allowed!', 'pop-application');
            }
        }
    }

    protected function validateCreateContent(array &$errors, FieldDataProviderInterface $fieldDataProvider): void
    {
        // Check the username
        $user_login = $fieldDataProvider->get('username');
        if ($user_login == '') {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('The username cannot be empty.', 'pop-application');
        } elseif (!validate_username($user_login)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('This username is invalid because it uses illegal characters. Please enter a valid username.', 'pop-application');
        } elseif (username_exists($user_login)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('This username is already registered. Please choose another one.', 'pop-application');
        }

        // Check the e-mail address
        $user_email = $fieldDataProvider->get('user_email');
        if (email_exists($user_email)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('This email is already registered, please choose another one.', 'pop-application');
        }

        // Validate Password
        $password = $fieldDataProvider->get('password');
        $repeatpassword =  $fieldDataProvider->get('repeat_password');

        if (!$password) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('The password cannot be emtpy.', 'pop-application');
        } elseif (strlen($password) < 8) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('The password must be at least 8 characters long.', 'pop-application');
        } else {
            if (!$repeatpassword) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->getTranslationAPI()->__('Please confirm the password.', 'pop-application');
            } elseif ($password !== $repeatpassword) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->getTranslationAPI()->__('Passwords do not match.', 'pop-application');
            }
        }

        // Validate the captcha
        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $captcha = $fieldDataProvider->get('captcha');
            try {
                GD_Captcha::assertIsValid($captcha);
            } catch (GenericClientException $e) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $e->getMessage();
            }
        }
    }

    /**
     * @param FeedbackItemResolution[] $errors
     */
    protected function validateUpdateContent(array &$errors, FieldDataProviderInterface $fieldDataProvider): void
    {
        $user_id = $fieldDataProvider->get('user_id');
        $user_email = $fieldDataProvider->get('user_email');

        $email_user_id = email_exists($user_email);
        if ($email_user_id && $email_user_id !== $user_id) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('That email address already exists in our system!', 'pop-application');
        }
    }

    protected function getUpdateuserData(FieldDataProviderInterface $fieldDataProvider)
    {
        $user_data = array(
            'id' => $fieldDataProvider->get('user_id'),
            'firstName' => $fieldDataProvider->get('first_name'),
            'email' => $fieldDataProvider->get('user_email'),
            'description' => $fieldDataProvider->get('description'),
            'url' => $fieldDataProvider->get('user_url')
        );

        return $user_data;
    }

    protected function getCreateuserData(FieldDataProviderInterface $fieldDataProvider)
    {
        $user_data = $this->getUpdateuserData($fieldDataProvider);

        // ID not needed
        unset($user_data['id']);

        // Assign the role only when creating a user
        $user_data['role'] = $this->getRole();

        // Add the password
        $user_data['password'] = $fieldDataProvider->get('password');

        // Username
        $user_data['login'] = $fieldDataProvider->get('username');

        return $user_data;
    }

    protected function executeUpdateuser($user_data)
    {
        $cmseditusersapi = FunctionAPIFactory::getInstance();
        return $cmseditusersapi->updateUser($user_data);
    }

    protected function createupdateuser($user_id, FieldDataProviderInterface $fieldDataProvider): void
    {
    }

    protected function updateuser(FieldDataProviderInterface $fieldDataProvider)
    {
        $user_data = $this->getUpdateuserData($fieldDataProvider);
        $user_id = $user_data['id'];

        $result = $this->executeUpdateuser($user_data);

        $this->createupdateuser($user_id, $fieldDataProvider);

        return $user_id;
    }

    protected function executeCreateuser($user_data)
    {
        $cmseditusersapi = FunctionAPIFactory::getInstance();
        return $cmseditusersapi->insertUser($user_data);
    }

    protected function createuser(FieldDataProviderInterface $fieldDataProvider)
    {
        $user_data = $this->getCreateuserData($fieldDataProvider);
        $result = $this->executeCreateuser($user_data);

        $user_id = $result;

        $this->createupdateuser($user_id, $fieldDataProvider);

        return $user_id;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataProviderInterface $fieldDataProvider): mixed
    {
        // If user is logged in => It's Update
        // Otherwise => It's Create
        if (App::getState('is-user-logged-in')) {
            return $this->update($fieldDataProvider);
        }

        return $this->create($fieldDataProvider);
    }

    protected function additionals($user_id, FieldDataProviderInterface $fieldDataProvider): void
    {
        App::doAction('gd_createupdate_user:additionals', $user_id, $fieldDataProvider);
    }
    protected function additionalsUpdate($user_id, FieldDataProviderInterface $fieldDataProvider): void
    {
        App::doAction('gd_createupdate_user:additionalsUpdate', $user_id, $fieldDataProvider);
    }
    protected function additionalsCreate($user_id, FieldDataProviderInterface $fieldDataProvider): void
    {
        App::doAction('gd_createupdate_user:additionalsCreate', $user_id, $fieldDataProvider);
    }

    public function validateErrors(FieldDataProviderInterface $fieldDataProvider): array
    {
        $errors = [];
        $this->validateContent($errors, $fieldDataProvider);
        if (App::getState('is-user-logged-in')) {
            $this->validateUpdateContent($errors, $fieldDataProvider);
        } else {
            $this->validateCreateContent($errors, $fieldDataProvider);
        }
        return $errors;
    }

    /**
     * @return mixed The ID of the updated entity, or an Error
     * @throws AbstractException In case of error
     */
    protected function update(FieldDataProviderInterface $fieldDataProvider): string | int
    {
        // Do the Post update
        $user_id = $this->updateuser($fieldDataProvider);

        // Allow for additional operations (eg: set Action categories)
        $this->additionalsUpdate($user_id, $fieldDataProvider);
        $this->additionals($user_id, $fieldDataProvider);

        // Trigger to update the display_name and nickname
        \userNameUpdated($user_id);
        return $user_id;
    }

    /**
     * @return mixed The ID of the created entity, or an Error
     * @throws AbstractException In case of error
     */
    protected function create(FieldDataProviderInterface $fieldDataProvider): string | int
    {
        $user_id = $this->createuser($fieldDataProvider);

        // Allow for additional operations (eg: set Action categories)
        $this->additionalsCreate($user_id, $fieldDataProvider);
        $this->additionals($user_id, $fieldDataProvider);

        return $user_id;
        // Comment Leo 21/09/2015: we don't use this function anymore to send the notifications to the admin/user. Instead, use our own hooks.
        // Send notification of new user
        // wpNewUserNotification( $user_id, $fieldDataProvider->get('password') );
    }
}
