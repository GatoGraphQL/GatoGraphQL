<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoP\EditPosts\FunctionAPIFactory;
use PoPSchema\CustomPostMeta\Utils;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSitesWassup\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

abstract class AbstractCreateUpdateStanceMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    // Update Post Validation
    protected function validateContent(array &$errors, array $form_data): void
    {
        if ($form_data['stancetarget'] ?? null) {
            // Check that the referenced post exists
            $referenced = $this->customPostTypeAPI->getCustomPost($form_data['stancetarget']);
            if (!$referenced) {
                $errors[] = $this->translationAPI->__('The referenced post does not exist', 'poptheme-wassup');
            } else {
                // If the referenced post has not been published yet, then error
                if ($this->customPostTypeAPI->getStatus($referenced) != Status::PUBLISHED) {
                    $errors[] = $this->translationAPI->__('The referenced post is not published yet', 'poptheme-wassup');
                }
            }
        }

        // If cheating then that's it, no need to validate anymore
        if (!$errors) {
            parent::validateContent($errors, $form_data);
        }
    }

    public function getCustomPostType(): string
    {
        return \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }

    protected function getCategoriesErrorMessages()
    {
        $category_error_msgs = parent::getCategoriesErrorMessages();
        $category_error_msgs['empty-category'] = $this->translationAPI->__('The stance has not been set', 'pop-userstance');
        $category_error_msgs['only-one'] = $this->translationAPI->__('Only one stance can be selected', 'pop-userstance');
        return $category_error_msgs;
    }

    protected function validateCreateContent(array &$errors, array $form_data): void
    {
        parent::validateCreateContent($errors, $form_data);

        $cmseditpostsapi = FunctionAPIFactory::getInstance();
        // For the Stance, there can be at most 1 post for:
        // - Each article: each referenced $post_id
        // - General Thought: only one without a $post_id, set through the homepage
        // If this validation already fails, the rest does not matter
        // Validate that the referenced post has been added (protection against hacking)
        // For highlights, we only add 1 reference, and not more.
        $referenced_id = $form_data['stancetarget'];

        // Check if there is already an existing stance
        $vars = ApplicationState::getVars();
        $query = array(
            'status' => array(Status::PUBLISHED, Status::DRAFT),
            'authors' => [$vars['global-userstate']['current-user-id']],
        );
        if ($referenced_id) {
            \UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $referenced_id);
        } else {
            \UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsGeneralstances($query);
        }

        // Stances are unique, just 1 per person/article.
        // Check if there is a Stance for the given post. If there is, it's an error, can't create a second Stance.
        if ($stances = $this->customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
            $stance_id = $stances[0];
            $error = sprintf(
                $this->translationAPI->__('You have already added your %s', 'pop-userstance'),
                \PoP_UserStance_PostNameUtils::getNameLc()
            );
            if ($referenced_id) {
                $error = sprintf(
                    $this->translationAPI->__('%s after reading “<a href="%s">%s</a>”', 'pop-userstance'),
                    $error,
                    $this->customPostTypeAPI->getPermalink($referenced_id),
                    $this->customPostTypeAPI->getTitle($referenced_id)
                );
            }
            $errors[] = sprintf(
                $this->translationAPI->__('%s. <a href="%s" target="%s">Edit?</a>', 'pop-userstance'),
                $error,
                urldecode($cmseditpostsapi->getEditPostLink($stance_id)),
                POP_TARGET_ADDONS
            );
        }
    }

    // Moved to WordPress-specific code
    // protected function getCreatepostData($form_data)
    // {
    //     $post_data = parent::getCreatepostData($form_data);

    //     // Allow to order the Author Thoughts Carousel, so that it always shows the General thought first, and the then article-related ones
    //     // For that, General thoughts have menu_order "0" (already default one), article-related ones have menu_order "1"
    //     if ($form_data['stancetarget'] ?? null) {
    //         $post_data['menu-order'] = 1;
    //     }

    //     return $post_data;
    // }

    protected function createAdditionals(string | int $post_id, array $form_data): void
    {
        parent::createAdditionals($post_id, $form_data);

        if ($target = $form_data['stancetarget']) {
            Utils::addCustomPostMeta($post_id, GD_METAKEY_POST_STANCETARGET, $target, true);
        }

        // Allow for URE to add the AuthorRole meta value
        $this->hooksAPI->doAction('GD_CreateUpdate_Stance:createAdditionals', $post_id, $form_data);
    }

    protected function updateAdditionals(string | int $post_id, array $form_data, array $log): void
    {
        parent::updateAdditionals($post_id, $form_data, $log);

        // Allow for URE to add the AuthorRole meta value
        $this->hooksAPI->doAction('GD_CreateUpdate_Stance:updateAdditionals', $post_id, $form_data, $log);
    }
}
