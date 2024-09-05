<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Constants\GenericCustomPostCRUDHookNames;
use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateOrUpdateCustomPostMutationResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractCreateOrUpdateGenericCustomPostMutationResolver extends AbstractCreateOrUpdateCustomPostMutationResolver
{
    public function getCustomPostType(): string
    {
        return '';
    }

    protected function triggerValidateCreateOrUpdateHook(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::triggerValidateCreateOrUpdateHook(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            GenericCustomPostCRUDHookNames::VALIDATE_CREATE_OR_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function triggerValidateCreateHook(
        string $customPostType,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::triggerValidateCreateHook(
            $customPostType,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            GenericCustomPostCRUDHookNames::VALIDATE_CREATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
            $customPostType,
        );
    }

    protected function validateUpdate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            GenericCustomPostCRUDHookNames::VALIDATE_UPDATE,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        parent::validateUpdate(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @param array<string,mixed> $customPostData
     * @return array<string,mixed>
     */
    protected function addCreateOrUpdateCustomPostData(array $customPostData, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return App::applyFilters(
            GenericCustomPostCRUDHookNames::GET_CREATE_OR_UPDATE_DATA,
            parent::addCreateOrUpdateCustomPostData($customPostData, $fieldDataAccessor),
            $fieldDataAccessor,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return App::applyFilters(
            GenericCustomPostCRUDHookNames::GET_UPDATE_DATA,
            parent::getUpdateCustomPostData($fieldDataAccessor),
            $fieldDataAccessor,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return App::applyFilters(
            GenericCustomPostCRUDHookNames::GET_CREATE_DATA,
            parent::getCreateCustomPostData($fieldDataAccessor),
            $fieldDataAccessor,
        );
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::update(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            GenericCustomPostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE,
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            GenericCustomPostCRUDHookNames::EXECUTE_UPDATE,
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $customPostID;
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $customPostID = parent::create(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        App::doAction(
            GenericCustomPostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE,
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
        App::doAction(
            GenericCustomPostCRUDHookNames::EXECUTE_CREATE,
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $customPostID;
    }
}
