<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

abstract class AbstractSetCategoriesOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    public function executeMutation(array $form_data): mixed
    {
        $customPostID = $form_data[MutationInputProperties::CUSTOMPOST_ID];
        $postCategoryIDs = $form_data[MutationInputProperties::CATEGORY_IDS];
        $append = $form_data[MutationInputProperties::APPEND];
        $customPostCategoryTypeAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeAPI->setCategories($customPostID, $postCategoryIDs, $append);
        return $customPostID;
    }

    abstract protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface;

    public function validateErrors(array $form_data): array
    {
        $errors = [];

        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return $errors;
        }

        if (!$form_data[MutationInputProperties::CUSTOMPOST_ID]) {
            $errors[] = sprintf(
                $this->__('The %s ID is missing.', 'custompost-category-mutations'),
                $this->getEntityName()
            );
        }
        return $errors;
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'custompost-category-mutations');
    }
}
