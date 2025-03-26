<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\Constants\CategoryCRUDHookNames;
use PoPCMSSchema\CategoryMutations\Exception\CategoryTermCRUDMutationException;
use PoPCMSSchema\CategoryMutations\TypeAPIs\CategoryTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\AbstractMutateTaxonomyTermMutationResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractMutateCategoryTermMutationResolver extends AbstractMutateTaxonomyTermMutationResolver implements CategoryTermMutationResolverInterface
{
    use MutateCategoryTermMutationResolverTrait;

    private ?CategoryTypeMutationAPIInterface $categoryTypeMutationAPI = null;

    final protected function getCategoryTypeMutationAPI(): CategoryTypeMutationAPIInterface
    {
        if ($this->categoryTypeMutationAPI === null) {
            /** @var CategoryTypeMutationAPIInterface */
            $categoryTypeMutationAPI = $this->instanceManager->getInstance(CategoryTypeMutationAPIInterface::class);
            $this->categoryTypeMutationAPI = $categoryTypeMutationAPI;
        }
        return $this->categoryTypeMutationAPI;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws CategoryTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getCategoryTypeMutationAPI()->updateCategoryTerm($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getCategoryTypeMutationAPI()->createCategoryTerm($taxonomyName, $taxonomyData);
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getCategoryTypeMutationAPI()->deleteCategoryTerm($taxonomyTermID, $taxonomyName);
    }

    protected function isHierarchical(): bool
    {
        return true;
    }

    protected function validateCreateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CategoryCRUDHookNames::VALIDATE_CREATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            CategoryCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateCreateErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateUpdateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CategoryCRUDHookNames::VALIDATE_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            CategoryCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateUpdateErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateOrUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getCreateOrUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryCRUDHookNames::GET_CREATE_OR_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryCRUDHookNames::GET_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getCreateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryCRUDHookNames::GET_CREATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::update($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(
            CategoryCRUDHookNames::EXECUTE_CREATE_OR_UPDATE,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            CategoryCRUDHookNames::EXECUTE_UPDATE,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $taxonomyTermID;
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::create($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(CategoryCRUDHookNames::EXECUTE_CREATE_OR_UPDATE, $taxonomyTermID, $fieldDataAccessor);
        App::doAction(CategoryCRUDHookNames::EXECUTE_CREATE, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }
}
