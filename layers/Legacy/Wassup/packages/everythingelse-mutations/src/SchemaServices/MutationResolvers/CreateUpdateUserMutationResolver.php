<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use GD_Captcha;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\EditUsers\FunctionAPIFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Exception\GenericClientException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP_Forms_ConfigurationUtils;

class CreateUpdateUserMutationResolver extends AbstractMutationResolver
{
    protected function getRole()
    {
        return 'subscriber';
    }

    protected function validateContent(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (empty($fieldDataAccessor->getValue('first_name'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('The name cannot be empty', 'pop-application');
        }

        // Validate email
        $user_email = $fieldDataAccessor->getValue('user_email');
        if ($user_email === '') {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('The e-mail cannot be empty', 'pop-application');
        } elseif (! is_email($user_email)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('The email address isn&#8217;t correct.', 'pop-application');
        }

        $limited_email_domains = get_site_option('limited_email_domains');
        if (is_array($limited_email_domains) && empty($limited_email_domains) == false) {
            $emaildomain = substr($user_email, 1 + strpos($user_email, '@'));
            if (in_array($emaildomain, $limited_email_domains) == false) {
                // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
                $errors[] = $this->getTranslationAPI()->__('That email address is not allowed!', 'pop-application');
            }
        }
    }

    protected function validateCreateContent(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Check the username
        $user_login = $fieldDataAccessor->getValue('username');
        if ($user_login == '') {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('The username cannot be empty.', 'pop-application');
        } elseif (!validate_username($user_login)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('This username is invalid because it uses illegal characters. Please enter a valid username.', 'pop-application');
        } elseif (username_exists($user_login)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('This username is already registered. Please choose another one.', 'pop-application');
        }

        // Check the e-mail address
        $user_email = $fieldDataAccessor->getValue('user_email');
        if (email_exists($user_email)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('This email is already registered, please choose another one.', 'pop-application');
        }

        // Validate Password
        $password = $fieldDataAccessor->getValue('password');
        $repeatpassword =  $fieldDataAccessor->getValue('repeat_password');

        if (!$password) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('The password cannot be emtpy.', 'pop-application');
        } elseif (strlen($password) < 8) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('The password must be at least 8 characters long.', 'pop-application');
        } else {
            if (!$repeatpassword) {
                // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
                $errors[] = $this->getTranslationAPI()->__('Please confirm the password.', 'pop-application');
            } elseif ($password !== $repeatpassword) {
                // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
                $errors[] = $this->getTranslationAPI()->__('Passwords do not match.', 'pop-application');
            }
        }

        // Validate the captcha
        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $captcha = $fieldDataAccessor->getValue('captcha');
            try {
                GD_Captcha::assertIsValid($captcha);
            } catch (GenericClientException $e) {
                // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
                $errors[] = $e->getMessage();
            }
        }
    }

    /**
     * @param FeedbackItemResolution[] $errors
     */
    protected function validateUpdateContent(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $user_id = $fieldDataAccessor->getValue('user_id');
        $user_email = $fieldDataAccessor->getValue('user_email');

        $email_user_id = email_exists($user_email);
        if ($email_user_id && $email_user_id !== $user_id) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->getTranslationAPI()->__('That email address already exists in our system!', 'pop-application');
        }
    }

    protected function getUpdateuserData(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $user_data = array(
            'id' => $fieldDataAccessor->getValue('user_id'),
            'firstName' => $fieldDataAccessor->getValue('first_name'),
            'email' => $fieldDataAccessor->getValue('user_email'),
            'description' => $fieldDataAccessor->getValue('description'),
            'url' => $fieldDataAccessor->getValue('user_url')
        );

        return $user_data;
    }

    protected function getCreateuserData(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $user_data = $this->getUpdateuserData($fieldDataAccessor);

        // ID not needed
        unset($user_data['id']);

        // Assign the role only when creating a user
        $user_data['role'] = $this->getRole();

        // Add the password
        $user_data['password'] = $fieldDataAccessor->getValue('password');

        // Username
        $user_data['login'] = $fieldDataAccessor->getValue('username');

        return $user_data;
    }

    protected function executeUpdateuser($user_data)
    {
        $cmseditusersapi = FunctionAPIFactory::getInstance();
        return $cmseditusersapi->updateUser($user_data);
    }

    protected function createupdateuser($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
    }

    protected function updateuser(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $user_data = $this->getUpdateuserData($fieldDataAccessor);
        $user_id = $user_data['id'];

        $result = $this->executeUpdateuser($user_data);

        $this->createupdateuser($user_id, $fieldDataAccessor);

        return $user_id;
    }

    protected function executeCreateuser($user_data)
    {
        $cmseditusersapi = FunctionAPIFactory::getInstance();
        return $cmseditusersapi->insertUser($user_data);
    }

    protected function createuser(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $user_data = $this->getCreateuserData($fieldDataAccessor);
        $result = $this->executeCreateuser($user_data);

        $user_id = $result;

        $this->createupdateuser($user_id, $fieldDataAccessor);

        return $user_id;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // If user is logged in => It's Update
        // Otherwise => It's Create
        if (App::getState('is-user-logged-in')) {
            return $this->update(
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore
            );
        }

        return $this->create($fieldDataAccessor);
    }

    protected function additionals($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_createupdate_user:additionals', $user_id, $fieldDataAccessor);
    }
    protected function additionalsUpdate($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_createupdate_user:additionalsUpdate', $user_id, $fieldDataAccessor);
    }
    protected function additionalsCreate($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_createupdate_user:additionalsCreate', $user_id, $fieldDataAccessor);
    }

    public function validateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->validateContent($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if (App::getState('is-user-logged-in')) {
            $this->validateUpdateContent($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        } else {
            $this->validateCreateContent($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        }
    }

    /**
     * @return mixed The ID of the updated entity, or an Error
     * @throws AbstractException In case of error
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        // Do the Post update
        $user_id = $this->updateuser($fieldDataAccessor);

        // Allow for additional operations (eg: set Action categories)
        $this->additionalsUpdate($user_id, $fieldDataAccessor);
        $this->additionals($user_id, $fieldDataAccessor);

        // Trigger to update the display_name and nickname
        \userNameUpdated($user_id);
        return $user_id;
    }

    /**
     * @return mixed The ID of the created entity, or an Error
     * @throws AbstractException In case of error
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int {
        $user_id = $this->createuser($fieldDataAccessor);

        // Allow for additional operations (eg: set Action categories)
        $this->additionalsCreate($user_id, $fieldDataAccessor);
        $this->additionals($user_id, $fieldDataAccessor);

        return $user_id;
        // Comment Leo 21/09/2015: we don't use this function anymore to send the notifications to the admin/user. Instead, use our own hooks.
        // Send notification of new user
        // wpNewUserNotification( $user_id, $fieldDataAccessor->getValue('password') );
    }
}
