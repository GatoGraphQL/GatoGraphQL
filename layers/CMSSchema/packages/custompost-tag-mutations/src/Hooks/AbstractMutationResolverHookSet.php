<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\CustomPostCRUDHookNames;
use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\SetTagsOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use stdClass;

abstract class AbstractMutationResolverHookSet extends AbstractHookSet
{
    use SetTagsOnCustomPostMutationResolverTrait;

    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostTagTypeMutationAPIInterface $customPostTagTypeMutationAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

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
    final public function setCustomPostTagTypeMutationAPI(CustomPostTagTypeMutationAPIInterface $customPostTagTypeMutationAPI): void
    {
        $this->customPostTagTypeMutationAPI = $customPostTagTypeMutationAPI;
    }
    final protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        if ($this->customPostTagTypeMutationAPI === null) {
            /** @var CustomPostTagTypeMutationAPIInterface */
            $customPostTagTypeMutationAPI = $this->instanceManager->getInstance(CustomPostTagTypeMutationAPIInterface::class);
            $this->customPostTagTypeMutationAPI = $customPostTagTypeMutationAPI;
        }
        return $this->customPostTagTypeMutationAPI;
    }
    final public function setTaxonomyTermTypeAPI(TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI): void
    {
        $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
    }
    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
    }

    protected function init(): void
    {
        App::addAction(
            $this->getValidateCreateHookName(),
            $this->maybeValidateTags(...),
            10,
            2
        );
        App::addAction(
            $this->getValidateUpdateHookName(),
            $this->maybeValidateTags(...),
            10,
            2
        );
        App::addAction(
            $this->getExecuteCreateOrUpdateHookName(),
            $this->maybeSetTags(...),
            10,
            3
        );
        App::addFilter(
            $this->getErrorPayloadHookName(),
            $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback(...),
            10,
            2
        );
    }

    abstract protected function getValidateCreateHookName(): string;
    abstract protected function getValidateUpdateHookName(): string;
    abstract protected function getExecuteCreateOrUpdateHookName(): string;

    protected function getErrorPayloadHookName(): string
    {
        return CustomPostCRUDHookNames::ERROR_PAYLOAD;
    }

    public function maybeValidateTags(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function canExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::TAGS_BY)) {
            return false;
        }
        /** @var stdClass */
        $tagsBy = $fieldDataAccessor->getValue(MutationInputProperties::TAGS_BY);
        if (((array) $tagsBy) === []) {
            return false;
        }

        return true;
    }

    public function maybeSetTags(
        int|string $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }

        $this->setTagsOnCustomPostOrAddError(
            $customPostID,
            false,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
    ): ErrorPayloadInterface {
        return $this->createSetTagsOnCustomPostErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback)
            ?? $errorPayload;
    }
}
