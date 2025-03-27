<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\MutateTaxonomyTermMutationResolverTrait;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

trait MutateTaxonomyTermMetaMutationResolverTrait
{
    use MutateTaxonomyTermMutationResolverTrait;

    abstract protected function getTaxonomyMetaTypeAPI(): TaxonomyMetaTypeAPIInterface;

    protected function validateSingleMetaEntryDoesNotExist(
        string|int $taxonomyTermID,
        string $key,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta($taxonomyTermID, $key, true) === null) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                $this->getSingleMetaEntryAlreadyExistsError($taxonomyTermID, $key),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function getSingleMetaEntryAlreadyExistsError(
        string|int $taxonomyTermID,
        string $key,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
            [
                $taxonomyTermID,
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
        $nonAllowedMetaKeys = [];
        $taxonomyMetaTypeAPI = $this->getTaxonomyMetaTypeAPI();
        foreach ($metaKeys as $metaKey) {
            if ($taxonomyMetaTypeAPI->validateIsMetaKeyAllowed($metaKey)) {
                continue;
            }
            $nonAllowedMetaKeys[] = $metaKey;
        }
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
