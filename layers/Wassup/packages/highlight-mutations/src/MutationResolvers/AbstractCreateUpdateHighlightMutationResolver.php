<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolvers;

use PoP\Root\App;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPSitesWassup\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

abstract class AbstractCreateUpdateHighlightMutationResolver extends AbstractCreateUpdateCustomPostMutationResolver
{
    public function getCustomPostType(): string
    {
        return \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    }

    protected function validateContent(array &$errors, array $form_data): void
    {
        // Validate that the referenced post has been added (protection against hacking)
        // For highlights, we only add 1 reference, and not more.
        if (!$form_data['highlightedpost']) {
            $errors[] = $this->__('No post has been highlighted', 'poptheme-wassup');
        } else {
            // Highlights have no title input by the user. Instead, produce the title from the referenced post
            $referenced = $this->getCustomPostTypeAPI()->getCustomPost($form_data['highlightedpost']);
            if (!$referenced) {
                $errors[] = $this->__('The highlighted post does not exist', 'poptheme-wassup');
            } else {
                // If the referenced post has not been published yet, then error
                if ($this->getCustomPostTypeAPI()->getStatus($referenced) != CustomPostStatus::PUBLISH) {
                    $errors[] = $this->__('The highlighted post is not published yet', 'poptheme-wassup');
                }
            }
        }

        // If cheating then that's it, no need to validate anymore
        if (!$errors) {
            parent::validateContent($errors, $form_data);
        }
    }

    protected function createAdditionals(string | int $post_id, array $form_data): void
    {
        parent::createAdditionals($post_id, $form_data);

        Utils::addCustomPostMeta($post_id, GD_METAKEY_POST_HIGHLIGHTEDPOST, $form_data['highlightedpost'], true);

        // Allow to create a Notification
        App::doAction('GD_CreateUpdate_Highlight:createAdditionals', $post_id, $form_data);
    }

    protected function updateAdditionals(string | int $post_id, array $form_data, array $log): void
    {
        parent::updateAdditionals($post_id, $form_data, $log);

        // Allow to create a Notification
        App::doAction('GD_CreateUpdate_Highlight:updateAdditionals', $post_id, $form_data, $log);
    }
}
