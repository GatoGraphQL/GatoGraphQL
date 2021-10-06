<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class RemoveFeaturedImageOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    protected CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI;

    #[Required]
    final public function autowireRemoveFeaturedImageOnCustomPostMutationResolver(
        CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI,
    ): void {
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    }

    public function executeMutation(array $form_data): mixed
    {
        $customPostID = $form_data[MutationInputProperties::CUSTOMPOST_ID];
        $this->customPostMediaTypeMutationAPI->removeFeaturedImage($customPostID);
        return $customPostID;
    }

    public function validateErrors(array $form_data): array
    {
        $errors = [];

        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return $errors;
        }

        if (!$form_data[MutationInputProperties::CUSTOMPOST_ID]) {
            $errors[] = $this->translationAPI->__('The custom post ID is missing.', 'custompostmedia-mutations');
        }
        return $errors;
    }
}
