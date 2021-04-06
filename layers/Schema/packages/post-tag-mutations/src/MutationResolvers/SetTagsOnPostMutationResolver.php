<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\PostTagMutations\Facades\PostTagTypeAPIFacade;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

class SetTagsOnPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    public function execute(array $form_data): mixed
    {
        $postID = $form_data[MutationInputProperties::POST_ID];
        $postTagIDs = $form_data[MutationInputProperties::TAG_IDS];
        $append = $form_data[MutationInputProperties::APPEND];
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $postTagTypeAPI->setTags($postID, $postTagIDs, $append);
        return $postID;
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
        if (!$form_data[MutationInputProperties::POST_ID]) {
            $errors[] = $translationAPI->__('The post ID is missing.', 'post-tag-mutations');
        }
        return $errors;
    }
}
