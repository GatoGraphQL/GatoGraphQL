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
        if (!$this->getTaxonomyTermTypeAPI()->taxonomyExists($taxonomyName)) {
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

    protected function validateTaxonomyTermIDNotEmpty(
        string|int|null $taxonomyTermID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($taxonomyTermID === null || $taxonomyTermID === '') {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E4,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
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
                    $this->getTaxonomyTermDoesNotExistError($taxonomyName, $taxonomyTermID),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getTaxonomyTermDoesNotExistError(
        ?string $taxonomyName,
        string|int $taxonomyTermID,
    ): FeedbackItemResolution {
        if ($taxonomyName !== null && $taxonomyName !== '') {
            return new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E7,
                [
                    $taxonomyName,
                    $taxonomyTermID,
                ]
            );
        }
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E6,
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
                    $this->getTaxonomyTermBySlugDoesNotExistError($taxonomyName, $taxonomyTermSlug),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getTaxonomyTermBySlugDoesNotExistError(
        ?string $taxonomyName,
        string|int $taxonomyTermSlug,
    ): FeedbackItemResolution {
        if ($taxonomyName !== null && $taxonomyName !== '') {
            return new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E9,
                [
                    $taxonomyName,
                    $taxonomyTermSlug,
                ]
            );
        }
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
                    $this->getLoggedInUserHasNoPermissionToEditTaxonomyTermsError($taxonomyName),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getLoggedInUserHasNoPermissionToEditTaxonomyTermsError(
        string $taxonomyName,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E2,
            [
                $taxonomyName,
            ]
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

    protected function validateCanLoggedInUserAssignTermsToTaxonomy(
        string $taxonomyName,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Validate user permission
        $userID = App::getState('current-user-id');
        if (
            !$this->getTaxonomyTermTypeAPI()->canUserAssignTermsToTaxonomy(
                $userID,
                $taxonomyName
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getLoggedInUserHasNoPermissionToAssignTermsToTaxonomyError($taxonomyName),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    protected function getLoggedInUserHasNoPermissionToAssignTermsToTaxonomyError(
        string $taxonomyName,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E10,
            [
                $taxonomyName,
            ]
        );
    }
}
