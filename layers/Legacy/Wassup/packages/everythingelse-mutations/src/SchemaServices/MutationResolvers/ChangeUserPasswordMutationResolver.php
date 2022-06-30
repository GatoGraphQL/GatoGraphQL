<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\UserAccount\FunctionAPIFactory;

class ChangeUserPasswordMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = [];
        $cmsuseraccountapi = FunctionAPIFactory::getInstance();
        // Validate Password
        // Check current password really belongs to the user
        $current_password = $mutationDataProvider->getValue('current_password');
        $password = $mutationDataProvider->getValue('password');
        $repeatpassword =  $mutationDataProvider->getValue('repeat_password');

        if (!$current_password) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('Please provide the current password.', 'pop-application');
        } elseif (!$cmsuseraccountapi->checkPassword($mutationDataProvider->getValue('user_id'), $current_password)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->getTranslationAPI()->__('Current password is incorrect.', 'pop-application');
        }

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
        return $errors;
    }

    protected function executeChangepassword($user_data)
    {
        $cmseditusersapi = \PoP\EditUsers\FunctionAPIFactory::getInstance();
        return $cmseditusersapi->updateUser($user_data);
    }

    protected function getChangepasswordData(MutationDataProviderInterface $mutationDataProvider)
    {
        $user_data = array(
            'id' => $mutationDataProvider->getValue('user_id'),
            'password' => $mutationDataProvider->getValue('password')
        );

        return $user_data;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $user_data = $this->getChangepasswordData($mutationDataProvider);
        $result = $this->executeChangepassword($user_data);

        $user_id = $user_data['ID'];

        App::doAction('gd_changepassword_user', $user_id, $mutationDataProvider);

        return $user_id;
    }
}
