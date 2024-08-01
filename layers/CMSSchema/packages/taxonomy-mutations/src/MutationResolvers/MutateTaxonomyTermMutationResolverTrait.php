<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait MutateTaxonomyTermMutationResolverTrait
{
    use ValidateUserLoggedInMutationResolverTrait;

    abstract protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface;

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

    protected function validateTaxonomyExists(
        string $taxonomyName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->getTaxonomyTypeAPI()->taxonomyExists($taxonomyName)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getTaxonomyDoesNotExistError($taxonomyName),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getTaxonomyDoesNotExistError(
        string $taxonomyName,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E5,
            [
                $taxonomyName,
            ]
        );
    }

    protected function validateTaxonomyTermByIDExists(
        string|int $taxonomyTermID,
        string|null $taxonomyName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->getTaxonomyTermTypeAPI()->taxonomyTermExists($taxonomyTermID, $taxonomyName ?? '')) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getTaxonomyTermDoesNotExistError($taxonomyTermID),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getTaxonomyTermDoesNotExistError(
        string|int $taxonomyTermID,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E7,
            [
                $taxonomyTermID,
            ]
        );
    }

    protected function validateTaxonomyTermBySlugExists(
        string $taxonomyTermSlug,
        string|null $taxonomyName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->getTaxonomyTermTypeAPI()->taxonomyTermExists($taxonomyTermSlug, $taxonomyName ?? '')) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getTaxonomyTermBySlugDoesNotExistError($taxonomyTermSlug),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getTaxonomyTermBySlugDoesNotExistError(
        string|int $taxonomyTermSlug,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E8,
            [
                $taxonomyTermSlug,
            ]
        );
    }

    protected function validateCanLoggedInUserEditTaxonomy(
        string $taxonomyName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Validate user permission
        $userID = App::getState('current-user-id');
        if (
            !$this->getTaxonomyTermTypeAPI()->canUserEditTaxonomy(
                $userID,
                $taxonomyName
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getLoggedInUserHasNoPermissionToEditTaxonomyTermsError(),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getLoggedInUserHasNoPermissionToEditTaxonomyTermsError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E2,
        );
    }

    protected function validateCanLoggedInUserDeleteTaxonomyTerm(
        string|int $taxonomyTermID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Validate user permission
        $userID = App::getState('current-user-id');
        if (
            !$this->getTaxonomyTermTypeAPI()->canUserDeleteTaxonomyTerm(
                $userID,
                $taxonomyTermID
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getLoggedInUserHasNoPermissionToDeleteTaxonomyTermError($taxonomyTermID),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getLoggedInUserHasNoPermissionToDeleteTaxonomyTermError(
        string|int $taxonomyTermID,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E3,
            [
                $taxonomyTermID,
            ]
        );
    }
}
