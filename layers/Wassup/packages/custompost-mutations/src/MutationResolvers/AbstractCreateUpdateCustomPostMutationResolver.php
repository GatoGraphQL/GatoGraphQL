<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations\MutationResolvers;

use PoP\Root\App;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties as CustomPostMediaMutationInputProperties;
use PoPSchema\CustomPostMeta\Utils;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver as UpstreamAbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPosts\Enums\CustomPostStatus;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

abstract class AbstractCreateUpdateCustomPostMutationResolver extends UpstreamAbstractCreateUpdateCustomPostMutationResolver
{
    public const VALIDATECATEGORIESTYPE_ATLEASTONE = 1;
    public const VALIDATECATEGORIESTYPE_EXACTLYONE = 2;

    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        return $this->postCategoryTypeAPI ??= $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }

    protected function supportsTitle()
    {
        // Not all post types support a title
        return true;
    }

    protected function addParentCategories()
    {
        return App::getHookManager()->applyFilters(
            'GD_CreateUpdate_Post:add-parent-categories',
            false,
            $this
        );
    }

    protected function isFeaturedImageMandatory()
    {
        return false;
    }

    protected function validateCategories(array $form_data)
    {
        if (isset($form_data[MutationInputProperties::CATEGORIES])) {
            if (is_array($form_data[MutationInputProperties::CATEGORIES])) {
                return self::VALIDATECATEGORIESTYPE_ATLEASTONE;
            }

            return self::VALIDATECATEGORIESTYPE_EXACTLYONE;
        }

        return null;
    }

    protected function getCategoriesErrorMessages()
    {
        return App::getHookManager()->applyFilters(
            'GD_CreateUpdate_Post:categories-validation:error',
            array(
                'empty-categories' => $this->__('The categories have not been set', 'pop-application'),
                'empty-category' => $this->__('The category has not been set', 'pop-application'),
                'only-one' => $this->__('Only one category can be selected', 'pop-application'),
            )
        );
    }

    // Update Post Validation
    protected function validateContent(array &$errors, array $form_data): void
    {
        parent::validateContent($errors, $form_data);

        if ($this->supportsTitle() && empty($form_data[MutationInputProperties::TITLE])) {
            $errors[] = $this->__('The title cannot be empty', 'pop-application');
        }

        // Validate the following conditions only if status = pending/publish
        if ($form_data[MutationInputProperties::STATUS] == CustomPostStatus::DRAFT) {
            return;
        }

        if (empty($form_data[MutationInputProperties::CONTENT])) {
            $errors[] = $this->__('The content cannot be empty', 'pop-application');
        }

        if ($this->isFeaturedImageMandatory() && empty($form_data[CustomPostMediaMutationInputProperties::FEATUREDIMAGE_ID])) {
            $errors[] = $this->__('The featured image has not been set', 'pop-application');
        }

        if ($validateCategories = $this->validateCategories($form_data)) {
            $category_error_msgs = $this->getCategoriesErrorMessages();
            if (empty($form_data[MutationInputProperties::CATEGORIES])) {
                if ($validateCategories == self::VALIDATECATEGORIESTYPE_ATLEASTONE) {
                    $errors[] = $category_error_msgs['empty-categories'];
                } elseif ($validateCategories == self::VALIDATECATEGORIESTYPE_EXACTLYONE) {
                    $errors[] = $category_error_msgs['empty-category'];
                }
            } elseif (count($form_data[MutationInputProperties::CATEGORIES]) > 1 && $validateCategories == self::VALIDATECATEGORIESTYPE_EXACTLYONE) {
                $errors[] = $category_error_msgs['only-one'];
            }
        }
    }

    protected function validateUpdateContent(array &$errors, array $form_data): void
    {
        parent::validateUpdateContent($errors, $form_data);

        if (isset($form_data[MutationInputProperties::REFERENCES]) && in_array($form_data[MutationInputProperties::ID], $form_data[MutationInputProperties::REFERENCES])) {
            $errors[] = $this->__('The post cannot be a response to itself', 'pop-postscreation');
        }
    }

    // Update Post Validation
    protected function validateUpdate(array &$errors, array $form_data): void
    {
        parent::validateUpdate($errors, $form_data);

        $customPostID = $form_data[MutationInputProperties::ID];

        if (!in_array($this->getCustomPostTypeAPI()->getStatus($customPostID), array(CustomPostStatus::DRAFT, CustomPostStatus::PENDING, CustomPostStatus::PUBLISH))) {
            $errors[] = $this->__('Hmmmmm, this post seems to have been deleted...', 'pop-application');
            return;
        }

        // Validation below not needed, since this is done in the Checkpoint already
        // // Validate user permission
        // if (!gdCurrentUserCanEdit($customPostID)) {
        //     $errors[] = $this->__('Your user doesn\'t have permission for editing.', 'pop-application');
        // }

        // // The nonce comes directly as a parameter in the request, it's not a form field
        // $nonce = $_REQUEST[POP_INPUTNAME_NONCE];
        // if (!gdVerifyNonce($nonce, GD_NONCE_EDITURL, $customPostID)) {
        //     $errors[] = $this->__('Incorrect URL', 'pop-application');
        //     return;
        // }
    }

    protected function additionals(int | string $customPostID, array $form_data): void
    {
        parent::additionals($customPostID, $form_data);

        // Topics
        if (\PoP_ApplicationProcessors_Utils::addCategories()) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_CATEGORIES, $form_data[MutationInputProperties::TOPICS]);
        }

        // Only if the Volunteering is enabled
        if (defined('POP_VOLUNTEERING_INITIALIZED')) {
            if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                // Volunteers Needed?
                if (isset($form_data[MutationInputProperties::VOLUNTEERSNEEDED])) {
                    Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_VOLUNTEERSNEEDED, $form_data[MutationInputProperties::VOLUNTEERSNEEDED], true, true);
                }
            }
        }

        if (\PoP_ApplicationProcessors_Utils::addAppliesto()) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_APPLIESTO, $form_data[MutationInputProperties::APPLIESTO]);
        }
    }

    protected function maybeAddParentCategories(?array $categories): ?array
    {
        if (!$categories) {
            return $categories;
        }
        // If the categories are nested under other categories, ask if to add those too
        if ($this->addParentCategories()) {
            // Use a while, to also check if the parent category has a parent itself
            $i = 0;
            while ($i < count($categories)) {
                $catID = $categories[$i];
                $i++;

                if ($parentCatID = $this->getPostCategoryTypeAPI()->getCategoryParentID($catID)) {
                    $categories[] = $parentCatID;
                }
            }
        }

        return $categories;
    }

    protected function addCreateUpdateCustomPostData(array &$post_data, array $form_data): void
    {
        parent::addCreateUpdateCustomPostData($post_data, $form_data);

        if (!$this->supportsTitle()) {
            unset($post_data['title']);
        }
    }

    protected function getUpdateCustomPostData(array $form_data): array
    {
        $post_data = parent::getUpdateCustomPostData($form_data);

        $this->addCustomPostType($post_data);

        // Status: If provided, Validate the value is permitted, or get the default value otherwise
        if ($status = $post_data['status']) {
            $post_data['status'] = \GD_CreateUpdate_Utils::getUpdatepostStatus($status, $this->moderate());
        }

        return $post_data;
    }

    protected function moderate()
    {
        return \GD_CreateUpdate_Utils::moderate();
    }

    protected function getCreateCustomPostData(array $form_data): array
    {
        $post_data = parent::getCreateCustomPostData($form_data);

        // Status: Validate the value is permitted, or get the default value otherwise
        $post_data['status'] = \GD_CreateUpdate_Utils::getCreatepostStatus($post_data['status'], $this->moderate());

        return $post_data;
    }

    protected function getCategories(array $form_data): ?array
    {
        // $cats = parent::getCategories($form_data);
        $cats = $form_data[MutationInputProperties::CATEGORIES];
        return $this->maybeAddParentCategories($cats);
    }

    protected function createUpdateCustomPost(array $form_data, int | string $customPostID): void
    {
        parent::createUpdateCustomPost($form_data, $customPostID);

        if (isset($form_data[MutationInputProperties::REFERENCES])) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_REFERENCES, $form_data[MutationInputProperties::REFERENCES]);
        }
    }

    protected function getUpdateCustomPostDataLog(int | string $customPostID, array $form_data): array
    {
        $log = parent::getUpdateCustomPostDataLog($customPostID, $form_data);

        if (isset($form_data[MutationInputProperties::REFERENCES])) {
            $previous_references = Utils::getCustomPostMeta($customPostID, GD_METAKEY_POST_REFERENCES);
            $log['new-references'] = array_diff($form_data[MutationInputProperties::REFERENCES], $previous_references);
        }

        return $log;
    }
}
