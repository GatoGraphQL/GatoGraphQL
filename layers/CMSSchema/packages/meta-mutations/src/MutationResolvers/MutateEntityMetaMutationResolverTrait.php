<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\MutationResolvers;

use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\MetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait MutateEntityMetaMutationResolverTrait
{
    abstract protected function getMetaTypeAPI(): MetaTypeAPIInterface;

    protected function validateSingleMetaEntryDoesNotExist(
        string|int $entityID,
        string $key,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->doesMetaEntryExist($entityID, $key)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getSingleMetaEntryAlreadyExistsError($entityID, $key),
                $fieldDataAccessor->getField(),
            )
        );
    }

    abstract protected function doesMetaEntryExist(
        string|int $entityID,
        string $key,
    ): bool;

    protected function getSingleMetaEntryAlreadyExistsError(
        string|int $entityID,
        string $key,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
            [
                $entityID,
                $key,
            ]
        );
    }

    protected function validateMetaEntryExists(
        string|int $entityID,
        string $key,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->doesMetaEntryExist($entityID, $key)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getMetaEntryDoesNotExistError($entityID, $key),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function validateMetaEntryWithValueExists(
        string|int $entityID,
        string $key,
        mixed $value,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->doesMetaEntryWithValueExist($entityID, $key, $value)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getMetaEntryWithValueDoesNotExistError($entityID, $key, $value),
                $fieldDataAccessor->getField(),
            )
        );
    }

    abstract protected function doesMetaEntryWithValueExist(
        string|int $entityID,
        string $key,
        mixed $value,
    ): bool;

    protected function getMetaEntryDoesNotExistError(
        string|int $entityID,
        string $key,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E4,
            [
                $entityID,
                $key,
            ]
        );
    }

    protected function getMetaEntryWithValueDoesNotExistError(
        string|int $entityID,
        string $key,
        mixed $value,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E5,
            [
                $entityID,
                $key,
                $value,
            ]
        );
    }

    protected function validateMetaEntryDoesNotHaveValue(
        string|int $entityID,
        string $key,
        mixed $value,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->doesMetaEntryHaveValue($entityID, $key, $value)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getEntityMetaEntryAlreadyHasValueError($entityID, $key, $value),
                $fieldDataAccessor->getField(),
            )
        );
    }

    abstract protected function doesMetaEntryHaveValue(
        string|int $entityID,
        string $key,
        mixed $value,
    ): bool;

    protected function getEntityMetaEntryAlreadyHasValueError(
        string|int $entityID,
        string $key,
        mixed $value,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E6,
            [
                $entityID,
                $key,
                $value,
            ]
        );
    }

    /**
     * @param string[] $metaKeys
     */
    protected function validateAreMetaKeysAllowed(
        array $metaKeys,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $taxonomyMetaTypeAPI = $this->getMetaTypeAPI();
        $nonAllowedMetaKeys = array_filter(
            $metaKeys,
            fn (string $metaKey) => !$taxonomyMetaTypeAPI->validateIsMetaKeyAllowed($metaKey)
        );
        if ($nonAllowedMetaKeys === []) {
            return;
        }
        if (count($nonAllowedMetaKeys) === 1) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E2,
                        [
                            $metaKeys[0],
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E3,
                    [
                        implode(
                            $this->__('\', \'', 'taxonomymeta-mutations'),
                            $metaKeys
                        ),
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }
}
