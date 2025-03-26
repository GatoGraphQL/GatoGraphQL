<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\Constants\InputProperties;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\Constants\TaxonomyMetaCRUDHookNames;
use PoPCMSSchema\TaxonomyMetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use stdClass;

abstract class AbstractMutateTaxonomyTermMetaMutationResolver extends AbstractMutationResolver implements TaxonomyTermMetaMutationResolverInterface
{
    use MutateTaxonomyTermMetaMutationResolverTrait;

    private ?TaxonomyMetaTypeMutationAPIInterface $taxonomyTypeMutationAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final protected function getTaxonomyMetaTypeMutationAPI(): TaxonomyMetaTypeMutationAPIInterface
    {
        if ($this->taxonomyTypeMutationAPI === null) {
            /** @var TaxonomyMetaTypeMutationAPIInterface */
            $taxonomyTypeMutationAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeMutationAPIInterface::class);
            $this->taxonomyTypeMutationAPI = $taxonomyTypeMutationAPI;
        }
        return $this->taxonomyTypeMutationAPI;
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

    protected function validateAddMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_ADD_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_SET_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        // /** @var string|null */
        // $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        // if ($taxonomyName !== null) {
        //     /**
        //      * Validate the taxonomy exists, even though in practice
        //      * it will always exist (since the input is an Enum)
        //      */
        //     $this->validateTaxonomyExists(
        //         $taxonomyName,
        //         $fieldDataAccessor,
        //         $objectTypeFieldResolutionFeedbackStore,
        //     );
        // }

        $this->maybeValidateTaxonomyParent($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);

        $this->validateCanLoggedInUserEditTaxonomy(
            $taxonomyName,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function isHierarchical(): bool;

    /**
     * For the `create` mutation, the taxonomy input is mandatory.
     * For the `updated` and `delete` mutations, the taxonomy input is optional.
     * If not provided, take it from the mutated entity.
     */
    protected function getTaxonomyName(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string {
        /** @var string|null */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        if ($taxonomyName !== null) {
            return $taxonomyName;
        }

        // If the taxonomy is null, then the ID must've been provided
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        /** @var string */
        return $this->getTaxonomyTermTypeAPI()->getTaxonomyTermTaxonomy($taxonomyTermID);
    }

    protected function validateUpdateMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_UPDATE_META_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_SET_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        /**
         * Perform this validation, even though this situation
         * should never happen. That's why there's no
         * CategoryIDMissingError added to the Union type
         */
        $this->validateTaxonomyTermIDNotEmpty(
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->maybeValidateTaxonomyParent($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int $taxonomyTermID */

        $this->validateTaxonomyTermByIDExists(
            $taxonomyTermID,
            null,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
        /**
         * If explicitly providing the taxonomy, make sure it
         * exists for that ID.
         */
        if ($fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) !== null) {
            $this->validateTaxonomyTermByIDExists(
                $taxonomyTermID,
                $taxonomyName,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserEditTaxonomy(
            $taxonomyName,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateDeleteMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        /**
         * Perform this validation, even though this situation
         * should never happen. That's why there's no
         * CategoryIDMissingError added to the Union type
         */
        $this->validateTaxonomyTermIDNotEmpty(
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
        /**
         * If explicitly providing the taxonomy, make sure it
         * exists for that ID.
         */
        if ($fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) !== null) {
            $this->validateTaxonomyTermByIDExists(
                $taxonomyTermID,
                $taxonomyName,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserDeleteTaxonomyTerm(
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function maybeValidateTaxonomyParent(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if (!$this->isHierarchical()) {
            return;
        }

        /** @var string */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);

        /** @var stdClass|null */
        $taxonomyParentBy = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_BY);
        if ($taxonomyParentBy !== null) {
            if (isset($taxonomyParentBy->{InputProperties::ID})) {
                /** @var string|int */
                $taxonomyParentID = $taxonomyParentBy->{InputProperties::ID};
                $this->validateTaxonomyTermByIDExists(
                    $taxonomyParentID,
                    $taxonomyName,
                    $fieldDataAccessor,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            } elseif (isset($taxonomyParentBy->{InputProperties::SLUG})) {
                /** @var string */
                $taxonomyParentSlug = $taxonomyParentBy->{InputProperties::SLUG};
                $this->validateTaxonomyTermBySlugExists(
                    $taxonomyParentSlug,
                    $taxonomyName,
                    $fieldDataAccessor,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            }
        }
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateOrUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = [];

        if ($fieldDataAccessor->hasValue(MutationInputProperties::NAME)) {
            $taxonomyData['name'] = $fieldDataAccessor->getValue(MutationInputProperties::NAME);
        }

        if ($fieldDataAccessor->hasValue(MutationInputProperties::SLUG)) {
            $taxonomyData['slug'] = $fieldDataAccessor->getValue(MutationInputProperties::SLUG);
        }

        if ($this->isHierarchical()) {
            if ($fieldDataAccessor->hasValue(MutationInputProperties::PARENT_BY)) {
                /** @var stdClass|null */
                $taxonomyParentBy = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_BY);

                /**
                 * Remove the parent if:
                 *
                 * - `parentBy` is `null`
                 * - Either `id` or `slug` is `null`
                 */
                if ($taxonomyParentBy === null) {
                    $taxonomyData['parent-id'] = null;
                } elseif (property_exists($taxonomyParentBy, InputProperties::ID)) {
                    $taxonomyData['parent-id'] = $taxonomyParentBy->{InputProperties::ID};
                } elseif (property_exists($taxonomyParentBy, InputProperties::SLUG)) {
                    /** @var string|null */
                    $taxonomyParentSlug = $taxonomyParentBy->{InputProperties::SLUG};
                    if ($taxonomyParentSlug === null) {
                        $taxonomyData['parent-id'] = null;
                    } else {
                        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
                        $taxonomyData['parent-id'] = $this->getTaxonomyTermTypeAPI()->getTaxonomyTermID($taxonomyParentSlug, $taxonomyName);
                    }
                }
            }
        }

        if ($fieldDataAccessor->hasValue(MutationInputProperties::DESCRIPTION)) {
            $taxonomyData['description'] = $fieldDataAccessor->getValue(MutationInputProperties::DESCRIPTION);
        }

        $taxonomyData = App::applyFilters(TaxonomyMetaCRUDHookNames::GET_SET_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = $this->getCreateOrUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(TaxonomyMetaCRUDHookNames::GET_UPDATE_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = $this->getCreateOrUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(TaxonomyMetaCRUDHookNames::GET_ADD_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTaxonomyMetaTypeMutationAPI()->updateTaxonomyTerm($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    protected function createUpdateTaxonomy(FieldDataAccessorInterface $fieldDataAccessor, int|string $taxonomyTermID): void
    {
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
        $taxonomyData = $this->getUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyTermID = $this->executeUpdateTaxonomyTerm($taxonomyTermID, $taxonomyName, $taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $taxonomyTermID);

        App::doAction(
            TaxonomyMetaCRUDHookNames::EXECUTE_SET_META,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TaxonomyMetaCRUDHookNames::EXECUTE_UPDATE_META_META,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $taxonomyTermID;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTaxonomyMetaTypeMutationAPI()->createTaxonomyTerm($taxonomyName, $taxonomyData);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string */
        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
        $taxonomyData = $this->getCreateTaxonomyTermData($fieldDataAccessor);
        $taxonomyTermID = $this->executeCreateTaxonomyTerm($taxonomyName, $taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $taxonomyTermID);

        App::doAction(TaxonomyMetaCRUDHookNames::EXECUTE_SET_META, $taxonomyTermID, $fieldDataAccessor);
        App::doAction(TaxonomyMetaCRUDHookNames::EXECUTE_ADD_META, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }

    /**
     * @return bool Was the deletion successful?
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool {
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);

        $result = $this->executeDeleteTaxonomyTerm($taxonomyTermID, $taxonomyName);
        if ($result === false) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getTaxonomyTermDoesNotExistError($taxonomyName, $taxonomyTermID),
                    $fieldDataAccessor->getField(),
                )
            );
        }

        return $result;
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getTaxonomyMetaTypeMutationAPI()->deleteTaxonomyTerm($taxonomyTermID, $taxonomyName);
    }
}
