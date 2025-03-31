<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\Constants\CategoryMetaCRUDHookNames;
use PoPCMSSchema\CategoryMetaMutations\Exception\CustomPostMetaCRUDMutationException;
use PoPCMSSchema\CategoryMetaMutations\TypeAPIs\CategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;
use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\AbstractMutateCustomPostMetaMutationResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractMutateCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver implements CustomPostMetaMutationResolverInterface
{
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
     * @return string|int the ID of the created meta entry
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: some custom post creation validation failed)
     */
    protected function executeAddTermMeta(string|int $customPostID, string $key, mixed $value, bool $single): string|int
    {
        return $this->getCategoryMetaTypeMutationAPI()->addCustomPostMeta($customPostID, $key, $value, $single);
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function executeUpdateTermMeta(string|int $customPostID, string $key, mixed $value, mixed $prevValue = null): string|int|bool
    {
        return $this->getCategoryMetaTypeMutationAPI()->updateCustomPostMeta($customPostID, $key, $value, $prevValue);
    }

    /**
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function executeDeleteTermMeta(string|int $customPostID, string $key): void
    {
        $this->getCategoryMetaTypeMutationAPI()->deleteCustomPostMeta($customPostID, $key);
    }

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function executeSetTermMeta(string|int $customPostID, array $entries): void
    {
        $this->getCategoryMetaTypeMutationAPI()->setCustomPostMeta($customPostID, $entries);
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
        $customPostData = parent::getAddMetaData($fieldDataAccessor);

        $customPostData = App::applyFilters(CategoryMetaCRUDHookNames::GET_ADD_META_DATA, $customPostData, $fieldDataAccessor);

        return $customPostData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $customPostData = parent::getUpdateMetaData($fieldDataAccessor);

        $customPostData = App::applyFilters(CategoryMetaCRUDHookNames::GET_UPDATE_META_DATA, $customPostData, $fieldDataAccessor);

        return $customPostData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDeleteMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $customPostData = parent::getDeleteMetaData($fieldDataAccessor);

        $customPostData = App::applyFilters(CategoryMetaCRUDHookNames::GET_DELETE_META_DATA, $customPostData, $fieldDataAccessor);

        return $customPostData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getSetMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $customPostData = parent::getSetMetaData($fieldDataAccessor);

        $customPostData = App::applyFilters(CategoryMetaCRUDHookNames::GET_SET_META_DATA, $customPostData, $fieldDataAccessor);

        return $customPostData;
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: some custom post creation validation failed)
     */
    protected function addMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::addMeta($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(CategoryMetaCRUDHookNames::EXECUTE_ADD_META, $fieldDataAccessor->getValue(MutationInputProperties::ID), $fieldDataAccessor);

        return $customPostID;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function updateMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::updateMeta($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(
            CategoryMetaCRUDHookNames::EXECUTE_UPDATE_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $customPostID;
    }

    /**
     * @return string|int The ID of the custom post
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function deleteMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::deleteMeta($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(CategoryMetaCRUDHookNames::EXECUTE_DELETE_META, $fieldDataAccessor->getValue(MutationInputProperties::ID), $fieldDataAccessor);

        return $customPostID;
    }

    /**
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function setMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::setMeta($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        App::doAction(CategoryMetaCRUDHookNames::EXECUTE_SET_META, $fieldDataAccessor->getValue(MutationInputProperties::ID), $fieldDataAccessor);

        return $customPostID;
    }
}
