<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\Constants\CategoryMetaCRUDHookNames;
use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermCRUDMutationException;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\AbstractMutateTaxonomyTermMutationResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractMutateCategoryTermMetaMutationResolver extends AbstractMutateTaxonomyTermMutationResolver implements CategoryTermMetaMutationResolverInterface
{
    use MutateCategoryTermMetaMutationResolverTrait;

    private ?CategoryMetaTypeMutationAPIInterface $categoryTypeMutationAPI = null;

    final protected function getCategoryMetaTypeMutationAPI(): CategoryMetaTypeMutationAPIInterface
    {
        if ($this->categoryTypeMutationAPI === null) {
            /** @var CategoryMetaTypeMutationAPIInterface */
            $categoryTypeMutationAPI = $this->instanceManager->getInstance(CategoryMetaTypeMutationAPIInterface::class);
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
        return $this->getCategoryMetaTypeMutationAPI()->updateCategoryTerm($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getCategoryMetaTypeMutationAPI()->createCategoryTerm($taxonomyName, $taxonomyData);
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getCategoryMetaTypeMutationAPI()->deleteCategoryTerm($taxonomyTermID, $taxonomyName);
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
            CategoryMetaCRUDHookNames::VALIDATE_ADD_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            CategoryMetaCRUDHookNames::VALIDATE_SET_META,
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
            CategoryMetaCRUDHookNames::VALIDATE_UPDATE_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            CategoryMetaCRUDHookNames::VALIDATE_SET_META,
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

        $taxonomyData = App::applyFilters(CategoryMetaCRUDHookNames::GET_SET_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryMetaCRUDHookNames::GET_UPDATE_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getCreateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryMetaCRUDHookNames::GET_ADD_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::update($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(
            CategoryMetaCRUDHookNames::EXECUTE_SET_META,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            CategoryMetaCRUDHookNames::EXECUTE_UPDATE_META,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $taxonomyTermID;
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::create($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(CategoryMetaCRUDHookNames::EXECUTE_SET_META, $taxonomyTermID, $fieldDataAccessor);
        App::doAction(CategoryMetaCRUDHookNames::EXECUTE_ADD_META, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }
}
