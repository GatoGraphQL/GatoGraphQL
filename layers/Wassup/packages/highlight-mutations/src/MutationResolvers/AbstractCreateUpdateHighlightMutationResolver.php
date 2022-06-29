<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
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

    protected function validateContent(array &$errors, WithArgumentsInterface $withArgumentsAST): void
    {
        // Validate that the referenced post has been added (protection against hacking)
        // For highlights, we only add 1 reference, and not more.
        if (!$withArgumentsAST->getArgumentValue('highlightedpost')) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('No post has been highlighted', 'poptheme-wassup');
        } else {
            // Highlights have no title input by the user. Instead, produce the title from the referenced post
            $referenced = $this->getCustomPostTypeAPI()->getCustomPost($withArgumentsAST->getArgumentValue('highlightedpost'));
            if (!$referenced) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('The highlighted post does not exist', 'poptheme-wassup');
            } else {
                // If the referenced post has not been published yet, then error
                if ($this->getCustomPostTypeAPI()->getStatus($referenced) != CustomPostStatus::PUBLISH) {
                    // @todo Migrate from string to FeedbackItemProvider
                    // $errors[] = new FeedbackItemResolution(
                    //     MutationErrorFeedbackItemProvider::class,
                    //     MutationErrorFeedbackItemProvider::E1,
                    // );
                    $errors[] = $this->__('The highlighted post is not published yet', 'poptheme-wassup');
                }
            }
        }

        // If cheating then that's it, no need to validate anymore
        if (!$errors) {
            parent::validateContent($errors, $withArgumentsAST);
        }
    }

    protected function createAdditionals(string | int $post_id, WithArgumentsInterface $withArgumentsAST): void
    {
        parent::createAdditionals($post_id, $withArgumentsAST);

        Utils::addCustomPostMeta($post_id, GD_METAKEY_POST_HIGHLIGHTEDPOST, $withArgumentsAST->getArgumentValue('highlightedpost'), true);

        // Allow to create a Notification
        App::doAction('GD_CreateUpdate_Highlight:createAdditionals', $post_id, $withArgumentsAST);
    }

    protected function updateAdditionals(string | int $post_id, WithArgumentsInterface $withArgumentsAST, array $log): void
    {
        parent::updateAdditionals($post_id, $withArgumentsAST, $log);

        // Allow to create a Notification
        App::doAction('GD_CreateUpdate_Highlight:updateAdditionals', $post_id, $withArgumentsAST, $log);
    }
}
