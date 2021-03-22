<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\CustomPostMediaMutations\Facades\CustomPostMediaTypeAPIFacade;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

class RemoveFeaturedImageOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    public function execute(array $form_data): mixed
    {
        $customPostID = $form_data[MutationInputProperties::CUSTOMPOST_ID];
        $customPostMediaTypeAPI = CustomPostMediaTypeAPIFacade::getInstance();
        $customPostMediaTypeAPI->removeFeaturedImage($customPostID);
        return $customPostID;
    }

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];

        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return $errors;
        }

        $translationAPI = TranslationAPIFacade::getInstance();
        if (!$form_data[MutationInputProperties::CUSTOMPOST_ID]) {
            $errors[] = $translationAPI->__('The custom post ID is missing.', 'custompostmedia-mutations');
        }
        return $errors;
    }
}
