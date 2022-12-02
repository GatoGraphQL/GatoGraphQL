<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
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
        /** @var PostCategoryTypeAPIInterface */
        return $this->postCategoryTypeAPI ??= $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }

    protected function supportsTitle(): bool
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

    protected function isFeaturedImageMandatory(): bool
    {
        return false;
    }

    protected function validateCategories(FieldDataAccessorInterface $fieldDataAccessor): ?int
    {
        if ($fieldDataAccessor->hasValue(MutationInputProperties::CATEGORIES)) {
            if (is_array($fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES))) {
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
    /**
     * @param string[] $errors
     * @todo Must migrate logic to `validateCreateUpdateErrors`
     */
    protected function validateContent(array &$errors, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::validateContent($errors, $fieldDataAccessor);

        if ($this->supportsTitle() && empty($fieldDataAccessor->getValue(MutationInputProperties::TITLE))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('The title cannot be empty', 'pop-application');
        }

        // Validate the following conditions only if status = pending/publish
        if ($fieldDataAccessor->getValue(MutationInputProperties::STATUS) == CustomPostStatus::DRAFT) {
            return;
        }

        if (empty($fieldDataAccessor->getValue(MutationInputProperties::CONTENT))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('The content cannot be empty', 'pop-application');
        }

        if ($this->isFeaturedImageMandatory() && empty($fieldDataAccessor->getValue(CustomPostMediaMutationInputProperties::FEATUREDIMAGE_ID))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('The featured image has not been set', 'pop-application');
        }

        if ($validateCategories = $this->validateCategories($fieldDataAccessor)) {
            $category_error_msgs = $this->getCategoriesErrorMessages();
            if (empty($fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES))) {
                if ($validateCategories == self::VALIDATECATEGORIESTYPE_ATLEASTONE) {
                    // @todo Migrate from string to FeedbackItemProvider
                // $objectTypeFieldResolutionFeedbackStore->addError(
                //     new ObjectTypeFieldResolutionFeedback(
                //         new FeedbackItemResolution(
                //             MutationErrorFeedbackItemProvider::class,
                //             MutationErrorFeedbackItemProvider::E1,
                //         ),
                //         $fieldDataAccessor->getField(),
                //     )
                // );
                    $errors[] = $category_error_msgs['empty-categories'];
                } elseif ($validateCategories == self::VALIDATECATEGORIESTYPE_EXACTLYONE) {
                    // @todo Migrate from string to FeedbackItemProvider
                // $objectTypeFieldResolutionFeedbackStore->addError(
                //     new ObjectTypeFieldResolutionFeedback(
                //         new FeedbackItemResolution(
                //             MutationErrorFeedbackItemProvider::class,
                //             MutationErrorFeedbackItemProvider::E1,
                //         ),
                //         $fieldDataAccessor->getField(),
                //     )
                // );
                    $errors[] = $category_error_msgs['empty-category'];
                }
            } elseif (count($fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES)) > 1 && $validateCategories == self::VALIDATECATEGORIESTYPE_EXACTLYONE) {
                // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
                $errors[] = $category_error_msgs['only-one'];
            }
        }
    }

    /**
     * @param FeedbackItemResolution[] $errors
     * @todo Must migrate logic to `validateUpdateErrors`
     */
    protected function validateUpdateContent(array &$errors, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::validateUpdateContent($errors, $fieldDataAccessor);

        if ($fieldDataAccessor->hasValue(MutationInputProperties::REFERENCES) && in_array($fieldDataAccessor->getValue(MutationInputProperties::ID), $fieldDataAccessor->getValue(MutationInputProperties::REFERENCES))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('The post cannot be a response to itself', 'pop-postscreation');
        }
    }

    /**
     * @param FeedbackItemResolution[] $errors
     */
    protected function validateUpdate(array &$errors, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::validateUpdate($errors, $fieldDataAccessor);

        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        if (!in_array($this->getCustomPostTypeAPI()->getStatus($customPostID), array(CustomPostStatus::DRAFT, CustomPostStatus::PENDING, CustomPostStatus::PUBLISH))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
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

    protected function additionals(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionals($customPostID, $fieldDataAccessor);

        // Topics
        if (PoP_ApplicationProcessors_Utils::addCategories()) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_CATEGORIES, $fieldDataAccessor->getValue(MutationInputProperties::TOPICS));
        }

        // Only if the Volunteering is enabled
        if (defined('POP_VOLUNTEERING_INITIALIZED')) {
            if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                // Volunteers Needed?
                if ($fieldDataAccessor->hasValue(MutationInputProperties::VOLUNTEERSNEEDED)) {
                    Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_VOLUNTEERSNEEDED, $fieldDataAccessor->getValue(MutationInputProperties::VOLUNTEERSNEEDED), true, true);
                }
            }
        }

        if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_APPLIESTO, $fieldDataAccessor->getValue(MutationInputProperties::APPLIESTO));
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

    /**
     * @param array<string,mixed> $post_data
     */
    protected function addCreateUpdateCustomPostData(array &$post_data, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::addCreateUpdateCustomPostData($post_data, $fieldDataAccessor);

        if (!$this->supportsTitle()) {
            unset($post_data['title']);
        }
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $post_data = parent::getUpdateCustomPostData($fieldDataAccessor);

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

    /**
     * @return array<string,mixed>
     */
    protected function getCreateCustomPostData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $post_data = parent::getCreateCustomPostData($fieldDataAccessor);

        // Status: Validate the value is permitted, or get the default value otherwise
        $post_data['status'] = GD_CreateUpdate_Utils::getCreatepostStatus($post_data['status'], $this->moderate());

        return $post_data;
    }

    protected function getCategories(FieldDataAccessorInterface $fieldDataAccessor): ?array
    {
        // $cats = parent::getCategories($fieldDataAccessor);
        $cats = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORIES);
        return $this->maybeAddParentCategories($cats);
    }

    protected function createUpdateCustomPost(FieldDataAccessorInterface $fieldDataAccessor, int|string $customPostID): void
    {
        parent::createUpdateCustomPost($fieldDataAccessor, $customPostID);

        if ($fieldDataAccessor->hasValue(MutationInputProperties::REFERENCES)) {
            Utils::updateCustomPostMeta($customPostID, GD_METAKEY_POST_REFERENCES, $fieldDataAccessor->getValue(MutationInputProperties::REFERENCES));
        }
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateCustomPostDataLog(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $log = parent::getUpdateCustomPostDataLog($customPostID, $fieldDataAccessor);

        if ($fieldDataAccessor->hasValue(MutationInputProperties::REFERENCES)) {
            $previous_references = Utils::getCustomPostMeta($customPostID, GD_METAKEY_POST_REFERENCES);
            $log['new-references'] = array_diff($fieldDataAccessor->getValue(MutationInputProperties::REFERENCES), $previous_references);
        }

        return $log;
    }
}
