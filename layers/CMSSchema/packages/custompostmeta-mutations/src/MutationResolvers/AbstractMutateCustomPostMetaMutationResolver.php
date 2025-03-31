<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMetaMutations\Constants\CustomPostMetaCRUDHookNames;
use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;
use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\CustomPostMetaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateOrUpdateCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\MutationResolvers\AbstractMutateEntityMetaMutationResolver;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;

abstract class AbstractMutateCustomPostMetaMutationResolver extends AbstractMutateEntityMetaMutationResolver implements CustomPostMetaMutationResolverInterface
{
    use CreateOrUpdateCustomPostMutationResolverTrait;
    use MutateCustomPostMetaMutationResolverTrait;

    private ?CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI = null;
    private ?CustomPostMetaTypeMutationAPIInterface $customPostMetaTypeMutationAPI = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI = null;

    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        if ($this->customPostMetaTypeAPI === null) {
            /** @var CustomPostMetaTypeAPIInterface */
            $customPostMetaTypeAPI = $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
            $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
        }
        return $this->customPostMetaTypeAPI;
    }
    final protected function getCustomPostMetaTypeMutationAPI(): CustomPostMetaTypeMutationAPIInterface
    {
        if ($this->customPostMetaTypeMutationAPI === null) {
            /** @var CustomPostMetaTypeMutationAPIInterface */
            $customPostMetaTypeMutationAPI = $this->instanceManager->getInstance(CustomPostMetaTypeMutationAPIInterface::class);
            $this->customPostMetaTypeMutationAPI = $customPostMetaTypeMutationAPI;
        }
        return $this->customPostMetaTypeMutationAPI;
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
    final protected function getNameResolver(): NameResolverInterface
    {
        if ($this->nameResolver === null) {
            /** @var NameResolverInterface */
            $nameResolver = $this->instanceManager->getInstance(NameResolverInterface::class);
            $this->nameResolver = $nameResolver;
        }
        return $this->nameResolver;
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
    final protected function getCustomPostTypeMutationAPI(): CustomPostTypeMutationAPIInterface
    {
        if ($this->customPostTypeMutationAPI === null) {
            /** @var CustomPostTypeMutationAPIInterface */
            $customPostTypeMutationAPI = $this->instanceManager->getInstance(CustomPostTypeMutationAPIInterface::class);
            $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
        }
        return $this->customPostTypeMutationAPI;
    }

    protected function validateEntityExists(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->validateCustomPostExists(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateUserCanEditEntity(
        string|int $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->validateCanLoggedInUserEditCustomPost(
            $customPostID,
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
    protected function executeAddEntityMeta(string|int $customPostID, string $key, mixed $value, bool $single): string|int
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
    protected function executeUpdateEntityMeta(string|int $customPostID, string $key, mixed $value, mixed $prevValue = null): string|int|bool
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
    protected function executeDeleteEntityMeta(string|int $customPostID, string $key): void
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
    protected function executeSetEntityMeta(string|int $customPostID, array $entries): void
    {
        $this->getCustomPostMetaTypeMutationAPI()->setCustomPostMeta($customPostID, $entries);
    }
}
