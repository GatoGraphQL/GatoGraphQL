<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
use PoPCMSSchema\TaxonomyMutations\Constants\HookNames;
use PoPCMSSchema\TaxonomyMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;

abstract class AbstractCreateOrUpdateTaxonomyMutationResolver extends AbstractMutationResolver implements TaxonomyMutationResolverInterface
{
    use CreateOrUpdateTaxonomyMutationResolverTrait;

    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?TaxonomyTypeAPIInterface $taxonomyTypeAPI = null;
    private ?TaxonomyTypeMutationAPIInterface $taxonomyTypeMutationAPI = null;

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
    final public function TaxonomyTypeAPI(TaxonomyTypeAPIInterface $taxonomyTypeAPI): void
    {
        $this->taxonomyTypeAPI = $taxonomyTypeAPI;
    }
    final protected function getTaxonomyNameAPI(): TaxonomyTypeAPIInterface
    {
        if ($this->taxonomyTypeAPI === null) {
            /** @var TaxonomyTypeAPIInterface */
            $taxonomyTypeAPI = $this->instanceManager->getInstance(TaxonomyTypeAPIInterface::class);
            $this->taxonomyTypeAPI = $taxonomyTypeAPI;
        }
        return $this->taxonomyTypeAPI;
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

    protected function validateCreateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Allow components (eg: CustomPostTaxonomyMutations) to inject their own validations
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

        $this->validateCanLoggedInUserEditTaxonomies(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateCreate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // Allow components (eg: CustomPostTaxonomyMutations) to inject their own validations
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
        // Allow components (eg: CustomPostTaxonomyMutations) to inject their own validations
        App::doAction(
            HookNames::VALIDATE_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $categoryID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $this->validateTaxonomyExists(
            $categoryID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function additionals(int|string $categoryID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
    }
    /**
     * @param array<string,mixed> $log
     */
    protected function updateAdditionals(int|string $categoryID, FieldDataAccessorInterface $fieldDataAccessor, array $log): void
    {
    }
    protected function createAdditionals(int|string $categoryID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return array<string,mixed>
     */
    protected function addCreateOrUpdateTaxonomyData(array $taxonomyData, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        if ($fieldDataAccessor->hasValue(MutationInputProperties::TAXONOMY)) {
            $taxonomyData['taxonomy-name'] = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::NAME)) {
            $taxonomyData['name'] = $fieldDataAccessor->getValue(MutationInputProperties::NAME);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::SLUG)) {
            $taxonomyData['slug'] = $fieldDataAccessor->getValue(MutationInputProperties::SLUG);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::PARENT_ID)) {
            $taxonomyData['parent-id'] = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_ID);
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
    protected function getUpdateTaxonomyData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = array(
            'id' => $fieldDataAccessor->getValue(MutationInputProperties::ID),
        );
        $taxonomyData = $this->addCreateOrUpdateTaxonomyData($taxonomyData, $fieldDataAccessor);

        $taxonomyData = App::applyFilters(HookNames::GET_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = [
            'taxonomy' => $this->getTaxonomyName(),
        ];
        $taxonomyData = $this->addCreateOrUpdateTaxonomyData($taxonomyData, $fieldDataAccessor);

        $taxonomyData = App::applyFilters(HookNames::GET_CREATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated category
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function executeUpdateTaxonomy(array $taxonomyData): string|int
    {
        return $this->getTaxonomyTypeMutationAPI()->updateTaxonomy($taxonomyData);
    }

    protected function createUpdateTaxonomy(FieldDataAccessorInterface $fieldDataAccessor, int|string $categoryID): void
    {
    }

    /**
     * @return array<string,string>|null[]
     */
    protected function getUpdateTaxonomyDataLog(int|string $categoryID, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return [
            'previous-status' => $this->getTaxonomyNameAPI()->getStatus($categoryID),
        ];
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyData = $this->getUpdateTaxonomyData($fieldDataAccessor);
        $categoryID = $taxonomyData['id'];

        // Create the operation log, to see what changed. Needed for
        // - Send email only when post published
        // - Add user notification of post being referenced, only when the reference is new (otherwise it will add the notification each time the user updates the post)
        $log = $this->getUpdateTaxonomyDataLog($categoryID, $fieldDataAccessor);

        $categoryID = $this->executeUpdateTaxonomy($taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $categoryID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($categoryID, $fieldDataAccessor);
        $this->updateAdditionals($categoryID, $fieldDataAccessor, $log);

        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $categoryID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_UPDATE, $categoryID, $log, $fieldDataAccessor);

        return $categoryID;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created category
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function executeCreateTaxonomy(array $taxonomyData): string|int
    {
        return $this->getTaxonomyTypeMutationAPI()->createTaxonomy($taxonomyData);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int {
        $taxonomyData = $this->getCreateTaxonomyData($fieldDataAccessor);
        $categoryID = $this->executeCreateTaxonomy($taxonomyData);

        $this->createUpdateTaxonomy($fieldDataAccessor, $categoryID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($categoryID, $fieldDataAccessor);
        $this->createAdditionals($categoryID, $fieldDataAccessor);

        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $categoryID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_CREATE, $categoryID, $fieldDataAccessor);

        return $categoryID;
    }
}
