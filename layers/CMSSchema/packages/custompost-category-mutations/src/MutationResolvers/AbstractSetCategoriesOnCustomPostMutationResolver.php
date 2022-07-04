<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostCategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

abstract class AbstractSetCategoriesOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataAccessorInterface $fieldDataProvider): mixed
    {
        $customPostID = $fieldDataProvider->get(MutationInputProperties::CUSTOMPOST_ID);
        $postCategoryIDs = $fieldDataProvider->get(MutationInputProperties::CATEGORY_IDS);
        $append = $fieldDataProvider->get(MutationInputProperties::APPEND);
        $customPostCategoryTypeAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeAPI->setCategories($customPostID, $postCategoryIDs, $append);
        return $customPostID;
    }

    abstract protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface;

    public function validateErrors(FieldDataAccessorInterface $fieldDataProvider): array
    {
        // Check that the user is logged-in
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            return [
                $errorFeedbackItemResolution,
            ];
        }

        $errors = [];
        if (!$fieldDataProvider->get(MutationInputProperties::CUSTOMPOST_ID)) {
            $errors[] = new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
                [
                    $this->getEntityName(),
                ]
            );
        }
        return $errors;
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'custompost-category-mutations');
    }
}
