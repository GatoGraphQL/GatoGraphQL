<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\Constants\InputProperties;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\Constants\TaxonomyCRUDHookNames;
use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use stdClass;

abstract class AbstractMutateTaxonomyTermMutationResolver extends AbstractMutationResolver implements TaxonomyTermMutationResolverInterface
{
    use MutateTaxonomyTermMutationResolverTrait;

    private ?TaxonomyTypeMutationAPIInterface $taxonomyTypeMutationAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setTaxonomyTypeMutationAPI(TaxonomyTypeMutationAPIInterface $taxonomyTypeMutationAPI): void
    {
        $this->taxonomyTypeMutationAPI = $taxonomyTypeMutationAPI;
    }
    final protected function getTaxonomyTypeMutationAPI(): TaxonomyTypeMutationAPIInterface
    {
        if ($this->taxonomyTypeMutationAPI === null) {
            /** @var TaxonomyTypeMutationAPIInterface */
            $taxonomyTypeMutationAPI = $this->instanceManager->getInstance(TaxonomyTypeMutationAPIInterface::class);
            $this->taxonomyTypeMutationAPI = $taxonomyTypeMutationAPI;
        }
        return $this->taxonomyTypeMutationAPI;
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

    protected function validateCreateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyCRUDHookNames::VALIDATE_CREATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TaxonomyCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        /** @var string|null */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        if ($taxonomyName !== null) {
            /**
             * Validate the taxonomy exists, even though in practice
             * it will always exist (since the input is an Enum)
             */
            $this->validateTaxonomyExists(
                $taxonomyName,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        $this->maybeValidateTaxonomyParent($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $taxonomyName ??= $this->getTaxonomyName();

        $this->validateCanLoggedInUserEditTaxonomy(
            $taxonomyName,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function isHierarchical(): bool;

    protected function validateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyCRUDHookNames::VALIDATE_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TaxonomyCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
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

        /**
         * Notice that the GenericCategory and GenericTag will not
         * provide a taxonomy name in the Resolver.
         *
         * @var string
         */
        $taxonomyName = $this->getTaxonomyName();
        if ($taxonomyName === '') {
            $taxonomyName = $this->getTaxonomyTermTypeAPI()->getTaxonomyTermTaxonomy($taxonomyTermID) ?? '';
        } else {
            /**
             * Make sure the provided ID corresponds to the expected taxonomy
             */
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

    protected function validateDeleteErrors(
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

        /** @var string|int $taxonomyTermID */

        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? $this->getTaxonomyName();
        if ($taxonomyName === '') {
            $taxonomyName = $this->getTaxonomyTermTypeAPI()->getTaxonomyTermTaxonomy($taxonomyTermID) ?? '';
        }
        /** @var string $taxonomyName */

        $this->validateTaxonomyTermByIDExists(
            $taxonomyTermID,
            $taxonomyName,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

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

        /** @var stdClass|null */
        $taxonomyParentBy = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_BY);
        if ($taxonomyParentBy !== null) {
            /** @var string */
            $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? $this->getTaxonomyName();
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
                        /** @var string */
                        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? $this->getTaxonomyName();
                        $taxonomyData['parent-id'] = $this->getTaxonomyTermTypeAPI()->getTaxonomyTermID($taxonomyParentSlug, $taxonomyName);
                    }
                }
            }
        }

        if ($fieldDataAccessor->hasValue(MutationInputProperties::DESCRIPTION)) {
            $taxonomyData['description'] = $fieldDataAccessor->getValue(MutationInputProperties::DESCRIPTION);
        }

        $taxonomyData = App::applyFilters(TaxonomyCRUDHookNames::GET_CREATE_OR_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = $this->getCreateOrUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(TaxonomyCRUDHookNames::GET_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = $this->getCreateOrUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(TaxonomyCRUDHookNames::GET_CREATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTaxonomyTypeMutationAPI()->updateTaxonomyTerm($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    protected function createUpdateTaxonomy(FieldDataAccessorInterface $fieldDataAccessor, int|string $taxonomyTermID): void
    {
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        /** @var string */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? $this->getTaxonomyName();
        if ($taxonomyName === '') {
            $taxonomyName = $this->getTaxonomyTermTypeAPI()->getTaxonomyTermTaxonomy($taxonomyTermID) ?? '';
        }
        $taxonomyData = $this->getUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyTermID = $this->executeUpdateTaxonomyTerm($taxonomyTermID, $taxonomyName, $taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $taxonomyTermID);

        App::doAction(TaxonomyCRUDHookNames::EXECUTE_CREATE_OR_UPDATE, $taxonomyTermID, $fieldDataAccessor);
        App::doAction(TaxonomyCRUDHookNames::EXECUTE_UPDATE, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTaxonomyTypeMutationAPI()->createTaxonomyTerm($taxonomyName, $taxonomyData);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int {
        /** @var string */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? $this->getTaxonomyName();
        $taxonomyData = $this->getCreateTaxonomyTermData($fieldDataAccessor);
        $taxonomyTermID = $this->executeCreateTaxonomyTerm($taxonomyName, $taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $taxonomyTermID);

        App::doAction(TaxonomyCRUDHookNames::EXECUTE_CREATE_OR_UPDATE, $taxonomyTermID, $fieldDataAccessor);
        App::doAction(TaxonomyCRUDHookNames::EXECUTE_CREATE, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }

    /**
     * @return bool Was the deletion successful?
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool {
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        /** @var string */
        $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? $this->getTaxonomyName();
        if ($taxonomyName === '') {
            $taxonomyName = $this->getTaxonomyTermTypeAPI()->getTaxonomyTermTaxonomy($taxonomyTermID) ?? '';
        }

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
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getTaxonomyTypeMutationAPI()->deleteTaxonomyTerm($taxonomyTermID, $taxonomyName);
    }
}
