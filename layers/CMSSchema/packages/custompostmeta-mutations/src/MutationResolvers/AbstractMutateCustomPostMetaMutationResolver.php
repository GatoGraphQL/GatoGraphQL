<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\MutationResolvers\AbstractMutateTermMetaMutationResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPostMetaMutations\Constants\CustomPostMetaCRUDHookNames;
use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;
use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\CustomPostMetaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutateCustomPostMutationResolverTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractMutateCustomPostMetaMutationResolver extends AbstractMutateTermMetaMutationResolver implements CustomPostMetaMutationResolverInterface
{
    use MutateCustomPostMutationResolverTrait;
    use MutateCustomPostMetaMutationResolverTrait;

    private ?CustomPostMetaTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostMetaTypeMutationAPIInterface $customPostTypeMutationAPI = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostMetaTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }
    final protected function getCustomPostMetaTypeMutationAPI(): CustomPostMetaTypeMutationAPIInterface
    {
        if ($this->customPostTypeMutationAPI === null) {
            /** @var CustomPostMetaTypeMutationAPIInterface */
            $customPostTypeMutationAPI = $this->instanceManager->getInstance(CustomPostMetaTypeMutationAPIInterface::class);
            $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
        }
        return $this->customPostTypeMutationAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    protected function validateTermExists(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->validateCustomPostByIDExists(
            $customPostID,
            null,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateUserCanEditTerm(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $customPostName = $this->getCustomPostTypeAPI()->getCustomPostCustomPost($customPostID);
        if ($customPostName === null) {
            return;
        }
        $this->validateCanLoggedInUserEditCustomPost(
            $customPostName,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateSetMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CustomPostMetaCRUDHookNames::VALIDATE_SET_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateSetMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateAddMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            CustomPostMetaCRUDHookNames::VALIDATE_ADD_META,
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
            CustomPostMetaCRUDHookNames::VALIDATE_UPDATE_META,
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
            CustomPostMetaCRUDHookNames::VALIDATE_DELETE_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateDeleteMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getSetMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $metaData = parent::getSetMetaData($fieldDataAccessor);

        $metaData = App::applyFilters(CustomPostMetaCRUDHookNames::GET_SET_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAddMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $metaData = parent::getAddMetaData($fieldDataAccessor);

        $metaData = App::applyFilters(CustomPostMetaCRUDHookNames::GET_ADD_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $metaData = parent::getUpdateMetaData($fieldDataAccessor);

        $metaData = App::applyFilters(CustomPostMetaCRUDHookNames::GET_UPDATE_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDeleteMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $metaData = parent::getDeleteMetaData($fieldDataAccessor);

        $metaData = App::applyFilters(CustomPostMetaCRUDHookNames::GET_DELETE_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return string|int The ID of the custom post
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: some custom post creation validation failed)
     */
    protected function addMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::addMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            CustomPostMetaCRUDHookNames::EXECUTE_ADD_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $customPostID;
    }

    /**
     * @return string|int the ID of the created custom post
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: some custom post creation validation failed)
     */
    protected function executeAddTermMeta(string|int $customPostID, string $key, mixed $value, bool $single): string|int
    {
        return $this->getCustomPostMetaTypeMutationAPI()->addCustomPostMeta($customPostID, $key, $value, $single);
    }

    /**
     * @return string|int The ID of the custom post
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function updateMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::updateMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            CustomPostMetaCRUDHookNames::EXECUTE_UPDATE_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $customPostID;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function executeUpdateTermMeta(string|int $customPostID, string $key, mixed $value, mixed $prevValue = null): string|int|bool
    {
        return $this->getCustomPostMetaTypeMutationAPI()->updateCustomPostMeta($customPostID, $key, $value, $prevValue);
    }

    /**
     * @return string|int The ID of the custom post
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function deleteMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::deleteMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            CustomPostMetaCRUDHookNames::EXECUTE_DELETE_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $customPostID;
    }

    /**
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function executeDeleteTermMeta(string|int $customPostID, string $key): void
    {
        $this->getCustomPostMetaTypeMutationAPI()->deleteCustomPostMeta($customPostID, $key);
    }

    /**
     * @return string|int The ID of the custom post
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function setMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::setMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            CustomPostMetaCRUDHookNames::EXECUTE_SET_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $customPostID;
    }

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws CustomPostMetaCRUDMutationException If there was an error (eg: custom post does not exist)
     */
    protected function executeSetTermMeta(string|int $customPostID, array $entries): void
    {
        $this->getCustomPostMetaTypeMutationAPI()->setCustomPostMeta($customPostID, $entries);
    }
}
