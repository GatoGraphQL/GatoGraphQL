<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoPSchema\CustomPostMutations\Facades\CustomPostTypeAPIFacade as MutationCustomPostTypeAPIFacade;
use PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet;
use PoPSchema\CustomPosts\Types\Status;

abstract class AbstractCreateUpdateCustomPostMutationResolver extends AbstractMutationResolver implements CustomPostMutationResolverInterface
{
    use ValidateUserLoggedInMutationResolverTrait;

    public const HOOK_EXECUTE_CREATE_OR_UPDATE = __CLASS__ . ':execute-create-or-update';
    public const HOOK_EXECUTE_CREATE = __CLASS__ . ':execute-create';
    public const HOOK_EXECUTE_UPDATE = __CLASS__ . ':execute-update';
    public const HOOK_VALIDATE_CONTENT = __CLASS__ . ':validate-content';

    // @TODO: Migrate when package "Categories" is completed
    // protected function getCategoryTaxonomy(): ?string
    // {
    //     return null;
    // }

    protected function validateCreateErrors(array $form_data): ?array
    {
        $errors = [];

        // If there are errors here, don't keep validating others
        $this->validateCreateUpdateErrors($errors, $form_data);
        if ($errors) {
            return $errors;
        }

        // If already exists any of these errors above, return errors
        $this->validateCreate($errors, $form_data);
        if ($errors) {
            return $errors;
        }

        $this->validateContent($errors, $form_data);
        $this->validateCreateContent($errors, $form_data);

        return $errors;
    }

    protected function validateUpdateErrors(array $form_data): ?array
    {
        $errors = [];

        // If there are errors here, don't keep validating others
        $this->validateCreateUpdateErrors($errors, $form_data);
        if ($errors) {
            return $errors;
        }

        // If already exists any of these errors above, return errors
        $this->validateUpdate($errors, $form_data);
        if ($errors) {
            return $errors;
        }

        $this->validateContent($errors, $form_data);
        $this->validateUpdateContent($errors, $form_data);

        return $errors;
    }

    protected function validateCreateUpdateErrors(array &$errors, array $form_data): void
    {
        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return;
        }

        $nameResolver = NameResolverFacade::getInstance();
        $translationAPI = TranslationAPIFacade::getInstance();

        // Validate user permission
        $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
        $vars = ApplicationState::getVars();
        $userID = $vars['global-userstate']['current-user-id'];
        $editCustomPostsCapability = $nameResolver->getName(LooseContractSet::NAME_EDIT_CUSTOMPOSTS_CAPABILITY);
        if (
            !$userRoleTypeDataResolver->userCan(
                $userID,
                $editCustomPostsCapability
            )
        ) {
            $errors[] = $translationAPI->__('Your user doesn\'t have permission for editing custom posts.', 'custompost-mutations');
            return;
        }

        // Check if the user can publish custom posts
        if (isset($form_data[MutationInputProperties::STATUS]) && $form_data[MutationInputProperties::STATUS] == Status::PUBLISHED) {
            $publishCustomPostsCapability = $nameResolver->getName(LooseContractSet::NAME_PUBLISH_CUSTOMPOSTS_CAPABILITY);
            if (
                !$userRoleTypeDataResolver->userCan(
                    $userID,
                    $publishCustomPostsCapability
                )
            ) {
                $errors[] = $translationAPI->__('Your user doesn\'t have permission for publishing custom posts.', 'custompost-mutations');
                return;
            }
        }
    }

    protected function getUserNotLoggedInErrorMessage(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('You must be logged in to create or update custom posts', 'custompost-mutations');
    }

    protected function validateContent(array &$errors, array $form_data): void
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        // Validate that the status is valid
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var CustomPostStatusEnum
         */
        $customPostStatusEnum = $instanceManager->getInstance(CustomPostStatusEnum::class);
        if (isset($form_data[MutationInputProperties::STATUS])) {
            $status = $form_data[MutationInputProperties::STATUS];
            if (!in_array($status, $customPostStatusEnum->getValues())) {
                $errors[] = sprintf(
                    $translationAPI->__('Status \'%s\' is not supported', 'custompost-mutations'),
                    $status
                );
            }
        }

        // Allow plugins to add validation for their fields
        $hooksAPI = HooksAPIFacade::getInstance();
        $hooksAPI->doAction(
            self::HOOK_VALIDATE_CONTENT,
            array(&$errors),
            $form_data
        );
    }

    protected function validateCreateContent(array &$errors, array $form_data): void
    {
    }
    protected function validateUpdateContent(array &$errors, array $form_data): void
    {
    }

    protected function validateCreate(array &$errors, array $form_data): void
    {
    }

    protected function validateUpdate(array &$errors, array $form_data): void
    {
        $translationAPI = TranslationAPIFacade::getInstance();

        $customPostID = $form_data[MutationInputProperties::ID] ?? null;
        if (!$customPostID) {
            $errors[] = $translationAPI->__('The ID is missing', 'custompost-mutations');
            return;
        }

        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $post = $customPostTypeAPI->getCustomPost($customPostID);
        if (!$post) {
            $errors[] = sprintf(
                $translationAPI->__('There is no entity with ID \'%s\'', 'custompost-mutations'),
                $customPostID
            );
            return;
        }

        // Check that the user has access to the edited custom post
        $mutationCustomPostTypeAPI = MutationCustomPostTypeAPIFacade::getInstance();
        $vars = ApplicationState::getVars();
        $userID = $vars['global-userstate']['current-user-id'];
        if (!$mutationCustomPostTypeAPI->canUserEditCustomPost($userID, $customPostID)) {
            $errors[] = sprintf(
                $translationAPI->__('You don\'t have permission to edit custom post with ID \'%s\'', 'custompost-mutations'),
                $customPostID
            );
            return;
        }
    }

    protected function additionals(mixed $customPostID, array $form_data): void
    {
    }
    protected function updateAdditionals(mixed $customPostID, array $form_data, array $log): void
    {
    }
    protected function createAdditionals(mixed $customPostID, array $form_data): void
    {
    }

    // protected function addCustomPostType(&$post_data)
    // {
    //     $post_data['custompost-type'] = $this->getCustomPostType();
    // }

    protected function addCreateUpdateCustomPostData(array &$post_data, array $form_data): void
    {
        if (isset($form_data[MutationInputProperties::CONTENT])) {
            $post_data['content'] = $form_data[MutationInputProperties::CONTENT];
        }
        if (isset($form_data[MutationInputProperties::TITLE])) {
            $post_data['title'] = $form_data[MutationInputProperties::TITLE];
        }
        if (isset($form_data[MutationInputProperties::STATUS])) {
            $post_data['status'] = $form_data[MutationInputProperties::STATUS];
        }
    }

    protected function getUpdateCustomPostData(array $form_data): array
    {
        $post_data = array(
            'id' => $form_data[MutationInputProperties::ID] ?? null,
        );
        $this->addCreateUpdateCustomPostData($post_data, $form_data);

        return $post_data;
    }

    protected function getCreateCustomPostData(array $form_data): array
    {
        $post_data = [
            'custompost-type' => $this->getCustomPostType(),
        ];
        $this->addCreateUpdateCustomPostData($post_data, $form_data);

        // $this->addCustomPostType($post_data);

        return $post_data;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function executeUpdateCustomPost(array $data): mixed
    {
        $customPostTypeAPI = MutationCustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->updateCustomPost($data);
    }

    // @TODO: Migrate when package "Categories" is completed
    // protected function getCategories(array $form_data): ?array
    // {
    //     return $form_data[MutationInputProperties::CATEGORIES];
    // }

    protected function createUpdateCustomPost(array $form_data, mixed $customPostID): void
    {
        // @TODO: Migrate when package "Categories" is completed
        // // Set categories for any taxonomy (not only for "category")
        // if ($cats = $this->getCategories($form_data)) {
        //     $taxonomyapi = \PoPSchema\Taxonomies\FunctionAPIFactory::getInstance();
        //     $taxonomy = $this->getCategoryTaxonomy();
        //     $taxonomyapi->setPostTerms($customPostID, $cats, $taxonomy);
        // }
    }

    protected function getUpdateCustomPostDataLog(mixed $customPostID, array $form_data): array
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $log = array(
            'previous-status' => $customPostTypeAPI->getStatus($customPostID),
        );

        return $log;
    }

    /**
     * @return mixed The ID of the updated entity, or an Error
     */
    protected function update(array $form_data): mixed
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $post_data = $this->getUpdateCustomPostData($form_data);
        $customPostID = $post_data['id'];

        // Create the operation log, to see what changed. Needed for
        // - Send email only when post published
        // - Add user notification of post being referenced, only when the reference is new (otherwise it will add the notification each time the user updates the post)
        $log = $this->getUpdateCustomPostDataLog($customPostID, $form_data);

        $result = $this->executeUpdateCustomPost($post_data);

        if ($result === 0) {
            return new Error(
                'update-error',
                $translationAPI->__('Oops, there was a problem... this is embarrassing, huh?', 'custompost-mutations')
            );
        }

        $this->createUpdateCustomPost($form_data, $customPostID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $form_data);
        $this->updateAdditionals($customPostID, $form_data, $log);

        // Inject Share profiles here
        $hooksAPI = HooksAPIFacade::getInstance();
        $hooksAPI->doAction(self::HOOK_EXECUTE_CREATE_OR_UPDATE, $customPostID, $form_data);
        $hooksAPI->doAction(self::HOOK_EXECUTE_UPDATE, $customPostID, $log, $form_data);
        return $customPostID;
    }

    /**
     * @param array<string, mixed> $data
     * @return mixed the ID of the created custom post
     */
    protected function executeCreateCustomPost(array $data): mixed
    {
        $customPostTypeAPI = MutationCustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->createCustomPost($data);
    }

    /**
     * @return mixed The ID of the created entity, or an Error
     */
    protected function create(array $form_data): mixed
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $post_data = $this->getCreateCustomPostData($form_data);
        $customPostID = $this->executeCreateCustomPost($post_data);

        if ($customPostID == 0) {
            return new Error(
                'create-error',
                $translationAPI->__('Oops, there was a problem... this is embarrassing, huh?', 'custompost-mutations')
            );
        }

        $this->createUpdateCustomPost($form_data, $customPostID);

        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $form_data);
        $this->createAdditionals($customPostID, $form_data);

        // Inject Share profiles here
        $hooksAPI = HooksAPIFacade::getInstance();
        $hooksAPI->doAction(self::HOOK_EXECUTE_CREATE_OR_UPDATE, $customPostID, $form_data);
        $hooksAPI->doAction(self::HOOK_EXECUTE_CREATE, $customPostID, $form_data);

        return $customPostID;
    }
}
