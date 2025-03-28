<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\Hooks;

use PoPCMSSchema\CategoryMutations\Constants\CategoryCRUDHookNames;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\MutationResolvers\MutateTermMetaMutationResolverTrait;
use PoPCMSSchema\MetaMutations\MutationResolvers\PayloadableMetaMutationResolverTrait;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use stdClass;

abstract class AbstractMutationResolverHookSet extends AbstractHookSet
{
    use MutateTermMetaMutationResolverTrait;
    use PayloadableMetaMutationResolverTrait;
    
    private ?TaxonomyMetaTypeMutationAPIInterface $taxonomyTypeMutationAPI = null;

    final protected function getTaxonomyMetaTypeMutationAPI(): TaxonomyMetaTypeMutationAPIInterface
    {
        if ($this->taxonomyTypeMutationAPI === null) {
            /** @var TaxonomyMetaTypeMutationAPIInterface */
            $taxonomyTypeMutationAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeMutationAPIInterface::class);
            $this->taxonomyTypeMutationAPI = $taxonomyTypeMutationAPI;
        }
        return $this->taxonomyTypeMutationAPI;
    }

    protected function init(): void
    {
        App::addAction(
            $this->getValidateCreateHookName(),
            $this->maybeValidateSetMeta(...)
        );
        App::addAction(
            $this->getValidateUpdateHookName(),
            $this->maybeValidateSetMeta(...)
        );
        App::addAction(
            $this->getExecuteCreateOrUpdateHookName(),
            $this->maybeSetMeta(...),
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
        return CategoryCRUDHookNames::ERROR_PAYLOAD;
    }

    public function maybeValidateSetMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }

        /** @var stdClass */
        $metaEntries = $fieldDataAccessor->getValue(MutationInputProperties::META);
        $keys = array_keys((array)$metaEntries);
        $this->validateAreMetaKeysAllowed(
            $keys,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function canExecuteMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): bool {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::META)) {
            return false;
        }
        /** @var stdClass */
        $meta = $fieldDataAccessor->getValue(MutationInputProperties::META);
        if (((array) $meta) === []) {
            return false;
        }

        return true;
    }

    public function maybeSetMeta(
        int|string $taxonomyTermID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }

        /** @var stdClass */
        $metaEntries = $fieldDataAccessor->getValue(MutationInputProperties::META);
        $this->getTaxonomyMetaTypeMutationAPI()->setTaxonomyTermMeta($taxonomyTermID, (array) $metaEntries);
    }

    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ErrorPayloadInterface $errorPayload,
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback,
    ): ErrorPayloadInterface {
        return $this->createMetaMutationErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback)
            ?? $errorPayload;
    }
}
