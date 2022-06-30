<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP_ApplicationProcessors_Utils;
use GD_CreateUpdate_Utils;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\App;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties as CustomPostMediaMutationInputProperties;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver as UpstreamAbstractCreateUpdateCustomPostMutationResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

abstract class AbstractCreateUpdateCustomPostMutationResolver extends UpstreamAbstractCreateUpdateCustomPostMutationResolver
{
    public final const VALIDATECATEGORIESTYPE_ATLEASTONE = 1;
    public final const VALIDATECATEGORIESTYPE_EXACTLYONE = 2;

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
        return App::applyFilters(
            'GD_CreateUpdate_Post:add-parent-categories',
            false,
            $this
        );
    }

    protected function isFeaturedImageMandatory()
    {
        return false;
    }

    protected function validateCategories(MutationDataProviderInterface $mutationDataProvider)
    {
        if ($mutationDataProvider->hasProperty(MutationInputProperties::CATEGORIES)) {
            if (is_array($mutationDataProvider->getValue(MutationInputProperties::CATEGORIES))) {
                return self::VALIDATECATEGORIESTYPE_ATLEASTONE;
            }

            return self::VALIDATECATEGORIESTYPE_EXACTLYONE;
        }

        return null;
    }

    protected function getCategoriesErrorMessages()
    {
        return App::applyFilters(
            'GD_CreateUpdate_Post:categories-validation:error',
            array(
                'empty-categories' => $this->__('The categories have not been set', 'pop-application'),
                'empty-category' => $this->__('The category has not been set', 'pop-application'),
                'only-one' => $this->__('Only one category can be selected', 'pop-application'),
            )
        );
    }

    // Update Post Validation
    protected function validateContent(array &$errors, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::validateContent($errors, $mutationDataProvider);

        if ($this->supportsTitle() && empty($mutationDataProvider->getValue(MutationInputProperties::TITLE))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The title cannot be empty', 'pop-application');
        }

        // Validate the following conditions only if status = pending/publish
        if ($mutationDataProvider->getValue(MutationInputProperties::STATUS) == CustomPostStatus::DRAFT) {
            return;
        }

        if (empty($mutationDataProvider->getValue(MutationInputProperties::CONTENT))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The content cannot be empty', 'pop-application');
        }

        if ($this->isFeaturedImageMandatory() && empty($mutationDataProvider->getValue(CustomPostMediaMutationInputProperties::FEATUREDIMAGE_ID))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The featured image has not been set', 'pop-application');
        }

        if ($validateCategories = $this->validateCategories($mutationDataProvider)) {
            $category_error_msgs = $this->getCategoriesErrorMessages();
            if (empty($mutationDataProvider->getValue(MutationInputProperties::CATEGORIES))) {
                if ($validateCategories == self::VALIDATECATEGORIESTYPE_ATLEASTONE) {
                    // @todo Migrate from string to FeedbackItemProvider
                    // $errors[] = new FeedbackItemResolution(
                    //     MutationErrorFeedbackItemProvider::class,
                    //     MutationErrorFeedbackItemProvider::E1,
                    // );
                    $errors[] = $category_error_msgs['empty-categories'];
                } elseif ($validateCategories == self::VALIDATECATEGORIESTYPE_EXACTLYONE) {
                    // @todo Migrate from string to FeedbackItemProvider
                    // $errors[] = new FeedbackItemResolution(
                    //     MutationErrorFeedbackItemProvider::class,
                    //     MutationErrorFeedbackItemProvider::E1,
                    // );
                    $errors[] = $category_error_msgs['empty-category'];
                }
            } elseif (count($mutationDataProvider->getValue(MutationInputProperties::CATEGORIES)) > 1 && $validateCategories == self::VALIDATECATEGORIESTYPE_EXACTLYONE) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $category_error_msgs['only-one'];
            }
        }
    }

    /**
     * @param FeedbackItemResolution[] $errors
     */
    protected function validateUpdateContent(array &$errors, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::validateUpdateContent($errors, $mutationDataProvider);

        if ($mutationDataProvider->hasProperty(MutationInputProperties::REFERENCES) && in_array($mutationDataProvider->getValue(MutationInputProperties::ID), $mutationDataProvider->getValue(MutationInputProperties::REFERENCES))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The post cannot be a response to itself', 'pop-postscreation');
        }
    }

    /**
     * @param FeedbackItemResolution[] $errors
     */
    protected function validateUpdate(array &$errors, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::validateUpdate($errors, $mutationDataProvider);

        $customPostID = $mutationDataProvider->getValue(MutationInputProperties::ID);

        if (!in_array($this->getCustomPostTypeAPI()->getStatus($customPostID), array(CustomPostStatus::DRAFT, CustomPostStatus::PENDING, CustomPostStatus::PUBLISH))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Hmmmmm, this post seems to have been deleted...', 'pop-application');
            return;
        }

        // Validation below not needed, since this is done in the Checkpoint already
        // // Validate user permission
        // if (!gdCurrentUserCanEdit($customPostID)) {
        //     $errors[] = $this->__('Your user doesn\'t have permission for editing.', 'pop-application');
        // }

        // // The nonce comes directly as a parameter in the request, it's not a form field
        // $nonce = App::query(POP_INPUTNAME_NONCE);
        // if (!gdVerifyNonce($nonce, GD_NONCE_EDITURL, $customPostID)) {
        //     $errors[] = $this->__('Incorrect URL', 'pop-application');
        //     return;
        // }
    }

    protected function additionals(int | string $customPostID, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionals($customPostID, $mutationDataProvider);

        // Topics
        if (PoP_ApplicationProcessors_Utils::addCategories()) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_CATEGORIES, $mutationDataProvider->getValue(MutationInputProperties::TOPICS));
        }

        // Only if the Volunteering is enabled
        if (defined('POP_VOLUNTEERING_INITIALIZED')) {
            if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                // Volunteers Needed?
                if ($mutationDataProvider->hasProperty(MutationInputProperties::VOLUNTEERSNEEDED)) {
                    Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_VOLUNTEERSNEEDED, $mutationDataProvider->getValue(MutationInputProperties::VOLUNTEERSNEEDED), true, true);
                }
            }
        }

        if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_APPLIESTO, $mutationDataProvider->getValue(MutationInputProperties::APPLIESTO));
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

    protected function addCreateUpdateCustomPostData(array &$post_data, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::addCreateUpdateCustomPostData($post_data, $mutationDataProvider);

        if (!$this->supportsTitle()) {
            unset($post_data['title']);
        }
    }

    protected function getUpdateCustomPostData(MutationDataProviderInterface $mutationDataProvider): array
    {
        $post_data = parent::getUpdateCustomPostData($mutationDataProvider);

        // Status: If provided, Validate the value is permitted, or get the default value otherwise
        if ($status = $post_data['status']) {
            $post_data['status'] = GD_CreateUpdate_Utils::getUpdatepostStatus($status, $this->moderate());
        }

        return $post_data;
    }

    protected function moderate()
    {
        return GD_CreateUpdate_Utils::moderate();
    }

    protected function getCreateCustomPostData(MutationDataProviderInterface $mutationDataProvider): array
    {
        $post_data = parent::getCreateCustomPostData($mutationDataProvider);

        // Status: Validate the value is permitted, or get the default value otherwise
        $post_data['status'] = GD_CreateUpdate_Utils::getCreatepostStatus($post_data['status'], $this->moderate());

        return $post_data;
    }

    protected function getCategories(MutationDataProviderInterface $mutationDataProvider): ?array
    {
        // $cats = parent::getCategories($mutationDataProvider);
        $cats = $mutationDataProvider->getValue(MutationInputProperties::CATEGORIES);
        return $this->maybeAddParentCategories($cats);
    }

    protected function createUpdateCustomPost(MutationDataProviderInterface $mutationDataProvider, int | string $customPostID): void
    {
        parent::createUpdateCustomPost($mutationDataProvider, $customPostID);

        if ($mutationDataProvider->hasProperty(MutationInputProperties::REFERENCES)) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_REFERENCES, $mutationDataProvider->getValue(MutationInputProperties::REFERENCES));
        }
    }

    protected function getUpdateCustomPostDataLog(int | string $customPostID, MutationDataProviderInterface $mutationDataProvider): array
    {
        $log = parent::getUpdateCustomPostDataLog($customPostID, $mutationDataProvider);

        if ($mutationDataProvider->hasProperty(MutationInputProperties::REFERENCES)) {
            $previous_references = Utils::getCustomPostMeta($customPostID, GD_METAKEY_POST_REFERENCES);
            $log['new-references'] = array_diff($mutationDataProvider->getValue(MutationInputProperties::REFERENCES), $previous_references);
        }

        return $log;
    }
}
