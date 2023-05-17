<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use stdClass;

abstract class AbstractCreateOrUpdateCustomPostMutationResolver extends AbstractMutationResolver implements CustomPostMutationResolverInterface
{
    use CreateOrUpdateCustomPostMutationResolverTrait;

    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI = null;

    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        /** @var NameResolverInterface */
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        /** @var UserRoleTypeAPIInterface */
        return $this->userRoleTypeAPI ??= $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
    }
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setCustomPostTypeMutationAPI(CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI): void
    {
        $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
    }
    final protected function getCustomPostTypeMutationAPI(): CustomPostTypeMutationAPIInterface
    {
        /** @var CustomPostTypeMutationAPIInterface */
        return $this->customPostTypeMutationAPI ??= $this->instanceManager->getInstance(CustomPostTypeMutationAPIInterface::class);
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

        $this->validateCanLoggedInUserEditCustomPosts(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        // Check if the user can publish custom posts
        if ($fieldDataAccessor->getValue(MutationInputProperties::STATUS) === CustomPostStatus::PUBLISH) {
            $this->validateCanLoggedInUserPublishCustomPosts(
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
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

        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $this->validateCustomPostExists(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserEditCustomPost(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function additionals(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
    }
    /**
     * @param array<string,mixed> $log
     */
    protected function updateAdditionals(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor, array $log): void
    {
    }
    protected function createAdditionals(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
    }

    // protected function addCustomPostType(&$post_data)
    // {
    //     $post_data['custompost-type'] = $this->getCustomPostType();
    // }
    /**
     * @param array<string,mixed> $post_data
     */
    protected function addCreateOrUpdateCustomPostData(array &$post_data, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        /**
         * @todo In addition to "html", support additional oneof properties for the mutation (eg: provide "blocks" for Gutenberg)
         */
        if ($fieldDataAccessor->hasValue(MutationInputProperties::CONTENT_AS)) {
            /** @var stdClass */
            $contentAs = $fieldDataAccessor->hasValue(MutationInputProperties::CONTENT_AS);
            if (isset($contentAs->{MutationInputProperties::HTML})) {
                $post_data['content'] = $contentAs->{MutationInputProperties::HTML};
            }
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::TITLE)) {
            $post_data['title'] = $fieldDataAccessor->getValue(MutationInputProperties::TITLE);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::STATUS)) {
            $post_data['status'] = $fieldDataAccessor->getValue(MutationInputProperties::STATUS);
        }
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $post_data = array(
            'id' => $fieldDataAccessor->getValue(MutationInputProperties::ID),
        );
        $this->addCreateOrUpdateCustomPostData($post_data, $fieldDataAccessor);

        return $post_data;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getCreateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $post_data = [
            'custompost-type' => $this->getCustomPostType(),
        ];
        $this->addCreateOrUpdateCustomPostData($post_data, $fieldDataAccessor);

        return $post_data;
    }

    /**
     * @param array<string,mixed> $post_data
     * @return string|int the ID of the updated custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function executeUpdateCustomPost(array $post_data): string|int
    {
        return $this->getCustomPostTypeMutationAPI()->updateCustomPost($post_data);
    }

    protected function createUpdateCustomPost(FieldDataAccessorInterface $fieldDataAccessor, int|string $customPostID): void
    {
    }

    /**
     * @return array<string,string>|null[]
     */
    protected function getUpdateCustomPostDataLog(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return [
            'previous-status' => $this->getCustomPostTypeAPI()->getStatus($customPostID),
        ];
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function update(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        $post_data = $this->getUpdateCustomPostData($fieldDataAccessor);
        $customPostID = $post_data['id'];

        // Create the operation log, to see what changed. Needed for
        // - Send email only when post published
        // - Add user notification of post being referenced, only when the reference is new (otherwise it will add the notification each time the user updates the post)
        $log = $this->getUpdateCustomPostDataLog($customPostID, $fieldDataAccessor);

        $customPostID = $this->executeUpdateCustomPost($post_data);

        $this->createUpdateCustomPost($fieldDataAccessor, $customPostID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $fieldDataAccessor);
        $this->updateAdditionals($customPostID, $fieldDataAccessor, $log);

        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $customPostID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_UPDATE, $customPostID, $log, $fieldDataAccessor);

        return $customPostID;
    }

    /**
     * @param array<string,mixed> $post_data
     * @return string|int the ID of the created custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function executeCreateCustomPost(array $post_data): string|int
    {
        return $this->getCustomPostTypeMutationAPI()->createCustomPost($post_data);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function create(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string|int {
        $post_data = $this->getCreateCustomPostData($fieldDataAccessor);
        $customPostID = $this->executeCreateCustomPost($post_data);

        $this->createUpdateCustomPost($fieldDataAccessor, $customPostID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $fieldDataAccessor);
        $this->createAdditionals($customPostID, $fieldDataAccessor);

        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $customPostID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_CREATE, $customPostID, $fieldDataAccessor);

        return $customPostID;
    }
}
