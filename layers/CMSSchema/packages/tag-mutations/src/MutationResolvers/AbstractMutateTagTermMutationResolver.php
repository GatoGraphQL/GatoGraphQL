<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\Constants\TagCRUDHookNames;
use PoPCMSSchema\TagMutations\Exception\TagTermCRUDMutationException;
use PoPCMSSchema\TagMutations\TypeAPIs\TagTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMutations\Exception\TaxonomyTermCRUDMutationException;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\AbstractMutateTaxonomyTermMutationResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractMutateTagTermMutationResolver extends AbstractMutateTaxonomyTermMutationResolver implements TagTermMutationResolverInterface
{
    use MutateTagTermMutationResolverTrait;

    private ?TagTypeMutationAPIInterface $tagTypeMutationAPI = null;

    final protected function getTagTypeMutationAPI(): TagTypeMutationAPIInterface
    {
        if ($this->tagTypeMutationAPI === null) {
            /** @var TagTypeMutationAPIInterface */
            $tagTypeMutationAPI = $this->instanceManager->getInstance(TagTypeMutationAPIInterface::class);
            $this->tagTypeMutationAPI = $tagTypeMutationAPI;
        }
        return $this->tagTypeMutationAPI;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws TagTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTagTypeMutationAPI()->updateTagTerm($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws TagTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTagTypeMutationAPI()->createTagTerm($taxonomyName, $taxonomyData);
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TagTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getTagTypeMutationAPI()->deleteTagTerm($taxonomyTermID, $taxonomyName);
    }

    protected function isHierarchical(): bool
    {
        return false;
    }

    protected function validateCreateErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TagCRUDHookNames::VALIDATE_CREATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TagCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
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
            TagCRUDHookNames::VALIDATE_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TagCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
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

        $taxonomyData = App::applyFilters(TagCRUDHookNames::GET_CREATE_OR_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getUpdateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(TagCRUDHookNames::GET_UPDATE_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateTaxonomyTermData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = parent::getCreateTaxonomyTermData($fieldDataAccessor);

        $taxonomyData = App::applyFilters(TagCRUDHookNames::GET_CREATE_DATA, $taxonomyData, $fieldDataAccessor);

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
            TagCRUDHookNames::EXECUTE_CREATE_OR_UPDATE,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            TagCRUDHookNames::EXECUTE_UPDATE,
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

        App::doAction(TagCRUDHookNames::EXECUTE_CREATE_OR_UPDATE, $taxonomyTermID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        App::doAction(TagCRUDHookNames::EXECUTE_CREATE, $taxonomyTermID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        return $taxonomyTermID;
    }
}
