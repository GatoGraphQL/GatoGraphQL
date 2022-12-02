<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use UserStance_Module_Processor_CustomSectionBlocksUtils;
use PoP_UserStance_PostNameUtils;
use PoP\Root\App;
use PoP\EditPosts\FunctionAPIFactory;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSitesWassup\CustomPostMutations\MutationResolvers\AbstractCreateOrUpdateCustomPostMutationResolver;

abstract class AbstractCreateUpdateStanceMutationResolver extends AbstractCreateOrUpdateCustomPostMutationResolver
{
    // Update Post Validation
    /**
     * @param string[] $errors
     * @todo Must migrate logic to `validateCreateUpdateErrors`
     */
    protected function validateContent(array &$errors, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        if ($fieldDataAccessor->getValue('stancetarget')) {
            // Check that the referenced post exists
            $referenced = $this->getCustomPostTypeAPI()->getCustomPost($fieldDataAccessor->getValue('stancetarget'));
            if (!$referenced) {
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
                $errors[] = $this->__('The referenced post does not exist', 'poptheme-wassup');
            } else {
                // If the referenced post has not been published yet, then error
                if ($this->getCustomPostTypeAPI()->getStatus($referenced) != CustomPostStatus::PUBLISH) {
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
                    $errors[] = $this->__('The referenced post is not published yet', 'poptheme-wassup');
                }
            }
        }

        // If cheating then that's it, no need to validate anymore
        if (!$errors) {
            parent::validateContent($errors, $fieldDataAccessor);
        }
    }

    public function getCustomPostType(): string
    {
        return \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }

    protected function getCategoriesErrorMessages()
    {
        $category_error_msgs = parent::getCategoriesErrorMessages();
        $category_error_msgs['empty-category'] = $this->__('The stance has not been set', 'pop-userstance');
        $category_error_msgs['only-one'] = $this->__('Only one stance can be selected', 'pop-userstance');
        return $category_error_msgs;
    }

    /**
     * @param string[] $errors
     * @todo Must migrate logic to `validateCreateErrors`
     */
    protected function validateCreateContent(array &$errors, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::validateCreateContent($errors, $fieldDataAccessor);

        $cmseditpostsapi = FunctionAPIFactory::getInstance();
        // For the Stance, there can be at most 1 post for:
        // - Each article: each referenced $post_id
        // - General Thought: only one without a $post_id, set through the homepage
        // If this validation already fails, the rest does not matter
        // Validate that the referenced post has been added (protection against hacking)
        // For highlights, we only add 1 reference, and not more.
        $referenced_id = $fieldDataAccessor->getValue('stancetarget');

        // Check if there is already an existing stance
        $query = array(
            'status' => array(CustomPostStatus::PUBLISH, CustomPostStatus::DRAFT),
            'authors' => [App::getState('current-user-id')],
        );
        if ($referenced_id) {
            UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $referenced_id);
        } else {
            UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsGeneralstances($query);
        }

        // Stances are unique, just 1 per person/article.
        // Check if there is a Stance for the given post. If there is, it's an error, can't create a second Stance.
        if ($stances = $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
            $stance_id = $stances[0];
            $error = sprintf(
                $this->__('You have already added your %s', 'pop-userstance'),
                PoP_UserStance_PostNameUtils::getNameLc()
            );
            if ($referenced_id) {
                $error = sprintf(
                    $this->__('%s after reading “<a href="%s">%s</a>”', 'pop-userstance'),
                    $error,
                    $this->getCustomPostTypeAPI()->getPermalink($referenced_id),
                    $this->getCustomPostTypeAPI()->getTitle($referenced_id)
                );
            }
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
            $errors[] = sprintf(
                $this->__('%s. <a href="%s" target="%s">Edit?</a>', 'pop-userstance'),
                $error,
                urldecode($cmseditpostsapi->getEditPostLink($stance_id)),
                POP_TARGET_ADDONS
            );
        }
    }

    // Moved to WordPress-specific code
    // protected function getCreatepostData(\PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor)
    // {
    //     $post_data = parent::getCreatepostData($fieldDataAccessor);

    //     // Allow to order the Author Thoughts Carousel, so that it always shows the General thought first, and the then article-related ones
    //     // For that, General thoughts have menu_order "0" (already default one), article-related ones have menu_order "1"
    //     if ($fieldDataAccessor->getValue('stancetarget')) {
    //         $post_data['menu-order'] = 1;
    //     }

    //     return $post_data;
    // }

    protected function createAdditionals(string|int $post_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::createAdditionals($post_id, $fieldDataAccessor);

        if ($target = $fieldDataAccessor->getValue('stancetarget')) {
            Utils::addCustomPostMeta($post_id, GD_METAKEY_POST_STANCETARGET, $target, true);
        }

        // Allow for URE to add the AuthorRole meta value
        App::doAction('GD_CreateUpdate_Stance:createAdditionals', $post_id, $fieldDataAccessor);
    }

    /**
     * @param array<string,mixed> $log
     */
    protected function updateAdditionals(string|int $post_id, FieldDataAccessorInterface $fieldDataAccessor, array $log): void
    {
        parent::updateAdditionals($post_id, $fieldDataAccessor, $log);

        // Allow for URE to add the AuthorRole meta value
        App::doAction('GD_CreateUpdate_Stance:updateAdditionals', $post_id, $fieldDataAccessor, $log);
    }
}
