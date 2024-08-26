<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\Hooks;

use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\SetCategoriesOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostCategoryMutations\ObjectModels\CategoryDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\Constants\CustomPostCRUDHookNames;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider as TaxonomyMutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use stdClass;

abstract class AbstractMutationResolverHookSet extends AbstractHookSet
{
    use SetCategoriesOnCustomPostMutationResolverTrait;

    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostCategoryTypeMutationAPIInterface $customPostCategoryTypeMutationAPI = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }
    final public function setCustomPostCategoryTypeMutationAPI(CustomPostCategoryTypeMutationAPIInterface $customPostCategoryTypeMutationAPI): void
    {
        $this->customPostCategoryTypeMutationAPI = $customPostCategoryTypeMutationAPI;
    }
    final protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        if ($this->customPostCategoryTypeMutationAPI === null) {
            /** @var CustomPostCategoryTypeMutationAPIInterface */
            $customPostCategoryTypeMutationAPI = $this->instanceManager->getInstance(CustomPostCategoryTypeMutationAPIInterface::class);
            $this->customPostCategoryTypeMutationAPI = $customPostCategoryTypeMutationAPI;
        }
        return $this->customPostCategoryTypeMutationAPI;
    }

    protected function init(): void
    {
        App::addAction(
            $this->getValidateCreateOrUpdateHookName(),
            $this->maybeValidateCategories(...),
            10,
            2
        );
        App::addAction(
            $this->getExecuteCreateOrUpdateHookName(),
            $this->maybeSetCategories(...),
            10,
            2
        );
        App::addFilter(
            $this->getErrorPayloadHookName(),
            $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
            10,
            2
        );
    }

    abstract protected function getValidateCreateOrUpdateHookName(): string;
    abstract protected function getExecuteCreateOrUpdateHookName(): string;
    abstract protected function getErrorPayloadHookName(): string;

    public function maybeValidateCategories(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }
        $categoriesBy = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES_BY);
        if (isset($categoriesBy->{MutationInputProperties::IDS})) {
            $customPostCategoryIDs = $categoriesBy->{MutationInputProperties::IDS};
            $this->validateCategoriesByIDExist(
                $customPostCategoryIDs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } elseif (isset($categoriesBy->{MutationInputProperties::SLUGS})) {
            $customPostCategorySlugs = $categoriesBy->{MutationInputProperties::SLUGS};
            $this->validateCategoriesBySlugExist(
                $customPostCategorySlugs,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    protected function canExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::CATEGORIES_BY)) {
            return false;
        }
        /** @var stdClass */
        $categoriesBy = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES_BY);
        if (((array) $categoriesBy) === []) {
            return false;
        }

        return true;
    }

    public function maybeSetCategories(
        int|string $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }

        $taxonomyName = $this->getCategoryTaxonomyName($fieldDataAccessor);
        /** @var stdClass */
        $categoriesBy = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES_BY);
        if (isset($categoriesBy->{MutationInputProperties::IDS})) {
            /** @var array<string|int> */
            $customPostCategoryIDs = $categoriesBy->{MutationInputProperties::IDS};
            $this->getCustomPostCategoryTypeMutationAPI()->setCategoriesByID(
                $taxonomyName,
                $customPostID,
                $customPostCategoryIDs,
                false
            );
        } elseif (isset($categoriesBy->{MutationInputProperties::SLUGS})) {
            /** @var string[] */
            $customPostCategorySlugs = $categoriesBy->{MutationInputProperties::SLUGS};
            $this->getCustomPostCategoryTypeMutationAPI()->setCategoriesBySlug(
                $taxonomyName,
                $customPostID,
                $customPostCategorySlugs,
                false
            );
        }
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
    ): ErrorPayloadInterface {
        $feedbackItemResolution = $objectTypeFieldResolutionFeedback->getFeedbackItemResolution();
        return match (
            [
            $feedbackItemResolution->getFeedbackProviderServiceClass(),
            $feedbackItemResolution->getCode()
            ]
        ) {
            [
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E2,
            ] => new CategoryDoesNotExistErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            [
                TaxonomyMutationErrorFeedbackItemProvider::class,
                TaxonomyMutationErrorFeedbackItemProvider::E10,
            ] => new LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload(
                $feedbackItemResolution->getMessage(),
            ),
            default => $errorPayload,
        };
    }

    abstract protected function getCategoryTaxonomyName(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string;
}
