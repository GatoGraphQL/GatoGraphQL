<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\Constants\InputProperties;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\Constants\HookNames;
use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use stdClass;

abstract class AbstractMutateTaxonomyTermMutationResolver extends AbstractMutationResolver implements TaxonomyTermMutationResolverInterface
{
    use MutateTaxonomyTermMutationResolverTrait;

    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?TaxonomyTypeMutationAPIInterface $taxonomyTypeMutationAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        if ($this->nameResolver === null) {
            /** @var NameResolverInterface */
            $nameResolver = $this->instanceManager->getInstance(NameResolverInterface::class);
            $this->nameResolver = $nameResolver;
        }
        return $this->nameResolver;
    }
    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        if ($this->userRoleTypeAPI === null) {
            /** @var UserRoleTypeAPIInterface */
            $userRoleTypeAPI = $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
            $this->userRoleTypeAPI = $userRoleTypeAPI;
        }
        return $this->userRoleTypeAPI;
    }
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
        $this->validateCreateUpdateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        $this->validateCreate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    protected function validateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // If there are errors here, don't keep validating others
        $this->validateCreateUpdateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        $this->validateUpdate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    protected function validateDeleteErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $this->validateTaxonomyTermByIDExists(
            $taxonomyTermID,
            null,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateCanLoggedInUserEditTaxonomyTerms(
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

    protected function validateCreateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            HookNames::VALIDATE_CREATE_OR_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserEditTaxonomyTerms(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        /** @var stdClass|null */
        $taxonomyParentBy = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_BY);
        if ($taxonomyParentBy !== null) {
            /** @var string|null */
            $taxonomyName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? '';
            if ($taxonomyParentBy->{InputProperties::ID} !== null) {
                /** @var string|int */
                $taxonomyParentID = $taxonomyParentBy->{InputProperties::ID};
                $this->validateTaxonomyTermByIDExists(
                    $taxonomyParentID,
                    $taxonomyName,
                    $fieldDataAccessor,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            } elseif ($taxonomyParentBy->{InputProperties::SLUG} !== null) {
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

    protected function validateCreate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            HookNames::VALIDATE_CREATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateUpdate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            HookNames::VALIDATE_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $this->validateTaxonomyTermByIDExists(
            $taxonomyTermID,
            null,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return array<string,mixed>
     */
    protected function addCreateOrUpdateTaxonomyTermData(array $taxonomyData, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        if ($fieldDataAccessor->hasValue(MutationInputProperties::TAXONOMY)) {
            $taxonomyData['taxonomy-name'] = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? '';
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::NAME)) {
            $taxonomyData['name'] = $fieldDataAccessor->getValue(MutationInputProperties::NAME);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::SLUG)) {
            $taxonomyData['slug'] = $fieldDataAccessor->getValue(MutationInputProperties::SLUG);
        }
        /** @var stdClass|null */
        $taxonomyParentBy = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_BY);
        if ($taxonomyParentBy !== null) {
            $taxonomyParentID = null;
            if ($taxonomyParentBy->{InputProperties::ID} !== null) {
                /** @var string|int */
                $taxonomyParentID = $taxonomyParentBy->{InputProperties::ID};
            } elseif ($taxonomyParentBy->{InputProperties::SLUG} !== null) {
                /** @var string */
                $taxonomyParentSlug = $taxonomyParentBy->{InputProperties::SLUG};
                $taxonomyParentID = $this->getTaxonomyTermTypeAPI()->getTaxonomyTermID($taxonomyParentSlug, $taxonomyData['taxonomy-name'] ?? '');                
            }
            if ($taxonomyParentID !== null) {
                $taxonomyData['parent-id'] = $taxonomyParentID;
            }
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::DESCRIPTION)) {
            $taxonomyData['description'] = $fieldDataAccessor->getValue(MutationInputProperties::DESCRIPTION);
        }

        $taxonomyData = App::applyFilters(HookNames::GET_CREATE_OR_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = array(
            'id' => $fieldDataAccessor->getValue(MutationInputProperties::ID),
        );
        $taxonomyData = $this->addCreateOrUpdateTaxonomyTermData($taxonomyData, $fieldDataAccessor);

        $taxonomyData = App::applyFilters(HookNames::GET_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDeleteTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = array(
            'id' => $fieldDataAccessor->getValue(MutationInputProperties::ID),
        );
        if ($fieldDataAccessor->hasValue(MutationInputProperties::TAXONOMY)) {
            $taxonomyData['taxonomy-name'] = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY) ?? '';
        }
        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = [
            'taxonomy' => $this->getTaxonomyName(),
        ];
        $taxonomyData = $this->addCreateOrUpdateTaxonomyTermData($taxonomyData, $fieldDataAccessor);

        $taxonomyData = App::applyFilters(HookNames::GET_CREATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTerm(array $taxonomyData): string|int
    {
        return $this->getTaxonomyTypeMutationAPI()->updateTaxonomyTerm($taxonomyData);
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
        $taxonomyData = $this->getUpdateTaxonomyTermData($fieldDataAccessor);
        $taxonomyTermID = $taxonomyData['id'];

        $taxonomyTermID = $this->executeUpdateTaxonomyTerm($taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $taxonomyTermID);

        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $taxonomyTermID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_UPDATE, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(array $taxonomyData): string|int
    {
        return $this->getTaxonomyTypeMutationAPI()->createTaxonomyTerm($taxonomyData);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int {
        $taxonomyData = $this->getCreateTaxonomyTermData($fieldDataAccessor);
        $taxonomyTermID = $this->executeCreateTaxonomyTerm($taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $taxonomyTermID);

        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $taxonomyTermID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_CREATE, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function delete(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyData = $this->getDeleteTaxonomyTermData($fieldDataAccessor);
        $taxonomyTermID = $taxonomyData['id'];
        $taxonomyName = $taxonomyData['taxonomy-name'] ?? '';

        $result = $this->executeDeleteTaxonomyTerm($taxonomyTermID, $taxonomyName);
        if ($result === false) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getTaxonomyTermDoesNotExistError($taxonomyTermID),
                    $fieldDataAccessor->getField(),
                )
            );
        }

        return $taxonomyTermID;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName = ''): bool
    {
        return $this->getTaxonomyTypeMutationAPI()->deleteTaxonomyTerm($taxonomyTermID, $taxonomyName);
    }
}
