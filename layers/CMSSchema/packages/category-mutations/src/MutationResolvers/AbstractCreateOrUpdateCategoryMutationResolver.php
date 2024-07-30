<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\Constants\HookNames;
use PoPCMSSchema\CategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CategoryMutations\Exception\CategoryCRUDMutationException;
use PoPCMSSchema\CategoryMutations\TypeAPIs\CategoryTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use stdClass;

abstract class AbstractCreateOrUpdateCategoryMutationResolver extends AbstractMutationResolver implements CategoryMutationResolverInterface
{
    use CreateOrUpdateCategoryMutationResolverTrait;

    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CategoryTypeMutationAPIInterface $customPostTypeMutationAPI = null;

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
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getTaxonomyNameAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }
    final public function setCategoryTypeMutationAPI(CategoryTypeMutationAPIInterface $customPostTypeMutationAPI): void
    {
        $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
    }
    final protected function getCategoryTypeMutationAPI(): CategoryTypeMutationAPIInterface
    {
        if ($this->customPostTypeMutationAPI === null) {
            /** @var CategoryTypeMutationAPIInterface */
            $customPostTypeMutationAPI = $this->instanceManager->getInstance(CategoryTypeMutationAPIInterface::class);
            $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
        }
        return $this->customPostTypeMutationAPI;
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
        // Allow components (eg: CustomPostCategoryMutations) to inject their own validations
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
        // Allow components (eg: CustomPostCategoryMutations) to inject their own validations
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
        // Allow components (eg: CustomPostCategoryMutations) to inject their own validations
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
     * @param array<string,mixed> $customPostData
     * @return array<string,mixed>
     */
    protected function addCreateOrUpdateCategoryData(array $customPostData, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        if ($fieldDataAccessor->hasValue(MutationInputProperties::TITLE)) {
            $customPostData['title'] = $fieldDataAccessor->getValue(MutationInputProperties::TITLE);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::EXCERPT)) {
            $customPostData['excerpt'] = $fieldDataAccessor->getValue(MutationInputProperties::EXCERPT);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::SLUG)) {
            $customPostData['slug'] = $fieldDataAccessor->getValue(MutationInputProperties::SLUG);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::STATUS)) {
            $customPostData['status'] = $fieldDataAccessor->getValue(MutationInputProperties::STATUS);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::TAXONOMY)) {
            $customPostData['taxonomy'] = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        }

        // Inject author, categories, tags, featured image, etc
        $customPostData = App::applyFilters(HookNames::GET_CREATE_OR_UPDATE_DATA, $customPostData, $fieldDataAccessor);

        return $customPostData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateCategoryData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $customPostData = array(
            'id' => $fieldDataAccessor->getValue(MutationInputProperties::ID),
        );
        $customPostData = $this->addCreateOrUpdateCategoryData($customPostData, $fieldDataAccessor);

        // Inject author, categories, tags, featured image, etc
        $customPostData = App::applyFilters(HookNames::GET_UPDATE_DATA, $customPostData, $fieldDataAccessor);

        return $customPostData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateCategoryData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $customPostData = [
            'custompost-type' => $this->getTaxonomyName(),
        ];
        $customPostData = $this->addCreateOrUpdateCategoryData($customPostData, $fieldDataAccessor);

        // Inject author, categories, tags, featured image, etc
        $customPostData = App::applyFilters(HookNames::GET_CREATE_DATA, $customPostData, $fieldDataAccessor);

        return $customPostData;
    }

    /**
     * @param array<string,mixed> $customPostData
     * @return string|int the ID of the updated category
     * @throws CategoryCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function executeUpdateCategory(array $customPostData): string|int
    {
        return $this->getCategoryTypeMutationAPI()->updateCategory($customPostData);
    }

    protected function createUpdateCategory(FieldDataAccessorInterface $fieldDataAccessor, int|string $categoryID): void
    {
    }

    /**
     * @return array<string,string>|null[]
     */
    protected function getUpdateCategoryDataLog(int|string $categoryID, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return [
            'previous-status' => $this->getTaxonomyNameAPI()->getStatus($categoryID),
        ];
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CategoryCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostData = $this->getUpdateCategoryData($fieldDataAccessor);
        $categoryID = $customPostData['id'];

        // Create the operation log, to see what changed. Needed for
        // - Send email only when post published
        // - Add user notification of post being referenced, only when the reference is new (otherwise it will add the notification each time the user updates the post)
        $log = $this->getUpdateCategoryDataLog($categoryID, $fieldDataAccessor);

        $categoryID = $this->executeUpdateCategory($customPostData);

        $this->createUpdateCategory($fieldDataAccessor, $categoryID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($categoryID, $fieldDataAccessor);
        $this->updateAdditionals($categoryID, $fieldDataAccessor, $log);

        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $categoryID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_UPDATE, $categoryID, $log, $fieldDataAccessor);

        return $categoryID;
    }

    /**
     * @param array<string,mixed> $customPostData
     * @return string|int the ID of the created category
     * @throws CategoryCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function executeCreateCategory(array $customPostData): string|int
    {
        return $this->getCategoryTypeMutationAPI()->createCategory($customPostData);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CategoryCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int {
        $customPostData = $this->getCreateCategoryData($fieldDataAccessor);
        $categoryID = $this->executeCreateCategory($customPostData);

        $this->createUpdateCategory($fieldDataAccessor, $categoryID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($categoryID, $fieldDataAccessor);
        $this->createAdditionals($categoryID, $fieldDataAccessor);

        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $categoryID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_CREATE, $categoryID, $fieldDataAccessor);

        return $categoryID;
    }
}
