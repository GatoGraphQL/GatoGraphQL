<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\Constants\CategoryMetaCRUDHookNames;
use PoPCMSSchema\CategoryMetaMutations\Exception\CategoryTermMetaCRUDMutationException;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers\AbstractMutateTaxonomyTermMetaMutationResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractMutateCategoryTermMetaMutationResolver extends AbstractMutateTaxonomyTermMetaMutationResolver implements CategoryTermMetaMutationResolverInterface
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
     * @return string|int the ID of the created taxonomy
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeAddTaxonomyTermMeta(string|int $taxonomyTermID, string $key, mixed $value, bool $single): string|int
    {
        return $this->getCategoryMetaTypeMutationAPI()->addTaxonomyTermMeta($taxonomyTermID, $key, $value, $single);
    }

    /**
     * @return string|int the ID of the updated taxonomy
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTermMeta(string|int $taxonomyTermID, string $key, mixed $value): string|int
    {
        return $this->getCategoryMetaTypeMutationAPI()->updateTaxonomyTermMeta($taxonomyTermID, $key, $value);
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeDeleteTaxonomyTermMeta(string|int $taxonomyTermID, string $key): void
    {
        $this->getCategoryMetaTypeMutationAPI()->deleteTaxonomyTermMeta($taxonomyTermID, $key);
    }

    /**
     * @param array<string,mixed[]> $entries
     * @throws CategoryTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeSetTaxonomyTermMeta(string|int $taxonomyTermID, array $entries): void
    {
        $this->getCategoryMetaTypeMutationAPI()->setTaxonomyTermMeta($taxonomyTermID, $entries);
    }

    protected function validateAddMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CategoryMetaCRUDHookNames::VALIDATE_ADD_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateAddMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateUpdateMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CategoryMetaCRUDHookNames::VALIDATE_UPDATE_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateUpdateMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateDeleteMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CategoryMetaCRUDHookNames::VALIDATE_DELETE_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateDeleteMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateSetMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CategoryMetaCRUDHookNames::VALIDATE_SET_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateSetMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAddMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getAddMetaData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryMetaCRUDHookNames::GET_ADD_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getUpdateMetaData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryMetaCRUDHookNames::GET_UPDATE_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDeleteMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getDeleteMetaData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryMetaCRUDHookNames::GET_DELETE_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getSetMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getSetMetaData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(CategoryMetaCRUDHookNames::GET_SET_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function updateMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::updateMeta($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

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
    protected function addMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $taxonomyTermID = parent::addMeta($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(CategoryMetaCRUDHookNames::EXECUTE_ADD_META, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }
}
