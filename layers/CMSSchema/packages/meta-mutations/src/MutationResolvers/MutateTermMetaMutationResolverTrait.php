<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\MutationResolvers;

use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\MetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait MutateTermMetaMutationResolverTrait
{
    abstract protected function getMetaTypeAPI(): MetaTypeAPIInterface;

    protected function validateSingleMetaEntryDoesNotExist(
        string|int $termID,
        string $key,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->doesSingleMetaEntryAlreadyExist($termID, $key)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getSingleMetaEntryAlreadyExistsError($termID, $key),
                $fieldDataAccessor->getField(),
            )
        );
    }

    abstract protected function doesSingleMetaEntryAlreadyExist(
        string|int $termID,
        string $key,
    ): bool;

    protected function getSingleMetaEntryAlreadyExistsError(
        string|int $termID,
        string $key,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
            [
                $termID,
                $key,
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
        $nonAllowedMetaKeys = array_map(
            fn (string $metaKey) => !$taxonomyMetaTypeAPI->validateIsMetaKeyAllowed($metaKey),
            $metaKeys
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
