<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\Hooks;

use PoPCMSSchema\CustomPostCategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\SetCategoriesOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostCategoryMutations\ObjectModels\CategoryDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties as CustomPostMutationsMutationInputProperties;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractMutationResolverHookSet extends AbstractHookSet
{
    use SetCategoriesOnCustomPostMutationResolverTrait;

    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

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

    protected function init(): void
    {
        App::addAction(
            HookNames::VALIDATE_CREATE_OR_UPDATE,
            $this->maybeValidateCategories(...),
            10,
            2
        );
        App::addAction(
            HookNames::EXECUTE_CREATE_OR_UPDATE,
            $this->maybeSetCategories(...),
            10,
            2
        );
        App::addFilter(
            HookNames::ERROR_PAYLOAD,
            $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
            10,
            2
        );
    }

    public function maybeValidateCategories(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }
        $customPostCategoryIDs = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES_BY);
        $this->validateCategoriesExist(
            $customPostCategoryIDs,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function canExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::CATEGORIES_BY)) {
            return false;
        }
        // Only for that specific CPT
        $customPostID = $fieldDataAccessor->getValue(CustomPostMutationsMutationInputProperties::ID);
        if (
            $customPostID !== null
            && $this->getCustomPostTypeAPI()->getCustomPostType($customPostID) !== $this->getCustomPostType()
        ) {
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
        $customPostCategoryIDs = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES_BY);
        $customPostCategoryTypeMutationAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeMutationAPI->setCategories($customPostID, $customPostCategoryIDs, false);
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        FeedbackItemResolution $feedbackItemResolution
    ): ErrorPayloadInterface {
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
            default => $errorPayload,
        };
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface;
}
