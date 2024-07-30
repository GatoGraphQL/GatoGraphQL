<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CategoryMutations\TypeAPIs\CategoryTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\CreateOrUpdateTaxonomyMutationResolverTrait;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;

trait CreateOrUpdateCategoryTermMutationResolverTrait
{
    use ValidateUserLoggedInMutationResolverTrait;
    use CreateOrUpdateTaxonomyMutationResolverTrait;

    abstract protected function getNameResolver(): NameResolverInterface;
    abstract protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface;
    abstract protected function getTaxonomyNameAPI(): CustomPostTypeAPIInterface;
    abstract protected function getCategoryTypeMutationAPI(): CategoryTypeMutationAPIInterface;

    /**
     * Check that the user is logged-in
     */
    protected function validateIsUserLoggedIn(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $errorFeedbackItemResolution,
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function validateTaxonomyTermExists(
        string|int|null $categoryID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$categoryID) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E6,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }

        if (!$this->getTaxonomyNameAPI()->customPostExists($categoryID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E7,
                        [
                            $categoryID,
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }
}
