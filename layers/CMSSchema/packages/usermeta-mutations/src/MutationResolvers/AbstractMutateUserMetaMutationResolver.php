<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\UserMetaMutations\Constants\UserMetaCRUDHookNames;
use PoPCMSSchema\UserMetaMutations\Exception\UserMetaCRUDMutationException;
use PoPCMSSchema\UserMetaMutations\TypeAPIs\UserMetaTypeMutationAPIInterface;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPCMSSchema\UserMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserMutations\TypeAPIs\UserTypeMutationAPIInterface;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\MutationResolvers\AbstractMutateEntityMetaMutationResolver;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;

abstract class AbstractMutateUserMetaMutationResolver extends AbstractMutateEntityMetaMutationResolver implements UserMetaMutationResolverInterface
{
    use MutateUserMetaMutationResolverTrait;

    private ?UserMetaTypeAPIInterface $userMetaTypeAPI = null;
    private ?UserMetaTypeMutationAPIInterface $userMetaTypeMutationAPI = null;
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?UserTypeMutationAPIInterface $userTypeMutationAPI = null;
    private ?CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI = null;

    final protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface
    {
        if ($this->userMetaTypeAPI === null) {
            /** @var UserMetaTypeAPIInterface */
            $userMetaTypeAPI = $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
            $this->userMetaTypeAPI = $userMetaTypeAPI;
        }
        return $this->userMetaTypeAPI;
    }
    final protected function getUserMetaTypeMutationAPI(): UserMetaTypeMutationAPIInterface
    {
        if ($this->userMetaTypeMutationAPI === null) {
            /** @var UserMetaTypeMutationAPIInterface */
            $userMetaTypeMutationAPI = $this->instanceManager->getInstance(UserMetaTypeMutationAPIInterface::class);
            $this->userMetaTypeMutationAPI = $userMetaTypeMutationAPI;
        }
        return $this->userMetaTypeMutationAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
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
    final protected function getUserTypeMutationAPI(): UserTypeMutationAPIInterface
    {
        if ($this->userTypeMutationAPI === null) {
            /** @var UserTypeMutationAPIInterface */
            $userTypeMutationAPI = $this->instanceManager->getInstance(UserTypeMutationAPIInterface::class);
            $this->userTypeMutationAPI = $userTypeMutationAPI;
        }
        return $this->userTypeMutationAPI;
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
        string|int $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $user = $this->getUserTypeAPI()->getUserByID($userID);
        if ($user !== null) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E1,
                    [
                        $userID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function validateUserCanEditEntity(
        string|int $userID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        if ($this->getUserTypeMutationAPI()->canLoggedInUserEditUser($userID)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E4,
                    [
                        $userID,
                    ]
                ),
                $fieldDataAccessor->getField(),
            )
        );
    }

    protected function validateSetMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            UserMetaCRUDHookNames::VALIDATE_SET_META,
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
            UserMetaCRUDHookNames::VALIDATE_ADD_META,
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
            UserMetaCRUDHookNames::VALIDATE_UPDATE_META,
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
            UserMetaCRUDHookNames::VALIDATE_DELETE_META,
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

        $metaData = App::applyFilters(UserMetaCRUDHookNames::GET_SET_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAddMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $metaData = parent::getAddMetaData($fieldDataAccessor);

        $metaData = App::applyFilters(UserMetaCRUDHookNames::GET_ADD_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $metaData = parent::getUpdateMetaData($fieldDataAccessor);

        $metaData = App::applyFilters(UserMetaCRUDHookNames::GET_UPDATE_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDeleteMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $metaData = parent::getDeleteMetaData($fieldDataAccessor);

        $metaData = App::applyFilters(UserMetaCRUDHookNames::GET_DELETE_META_DATA, $metaData, $fieldDataAccessor);

        return $metaData;
    }

    /**
     * @return string|int The ID of the user
     * @throws UserMetaCRUDMutationException If there was an error (eg: some user creation validation failed)
     */
    protected function addMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $userID = parent::addMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            UserMetaCRUDHookNames::EXECUTE_ADD_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $userID;
    }

    /**
     * @return string|int the ID of the created user
     * @throws UserMetaCRUDMutationException If there was an error (eg: some user creation validation failed)
     */
    protected function executeAddEntityMeta(string|int $userID, string $key, mixed $value, bool $single): string|int
    {
        return $this->getUserMetaTypeMutationAPI()->addUserMeta($userID, $key, $value, $single);
    }

    /**
     * @return string|int The ID of the user
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    protected function updateMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $userID = parent::updateMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            UserMetaCRUDHookNames::EXECUTE_UPDATE_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $userID;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    protected function executeUpdateEntityMeta(string|int $userID, string $key, mixed $value, mixed $prevValue = null): string|int|bool
    {
        return $this->getUserMetaTypeMutationAPI()->updateUserMeta($userID, $key, $value, $prevValue);
    }

    /**
     * @return string|int The ID of the user
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    protected function deleteMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $userID = parent::deleteMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            UserMetaCRUDHookNames::EXECUTE_DELETE_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $userID;
    }

    /**
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    protected function executeDeleteEntityMeta(string|int $userID, string $key): void
    {
        $this->getUserMetaTypeMutationAPI()->deleteUserMeta($userID, $key);
    }

    /**
     * @return string|int The ID of the user
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    protected function setMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $userID = parent::setMeta(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            UserMetaCRUDHookNames::EXECUTE_SET_META,
            $fieldDataAccessor->getValue(MutationInputProperties::ID),
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $userID;
    }

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws UserMetaCRUDMutationException If there was an error (eg: user does not exist)
     */
    protected function executeSetEntityMeta(string|int $userID, array $entries): void
    {
        $this->getUserMetaTypeMutationAPI()->setUserMeta($userID, $entries);
    }
}
