<?php
define('POP_EMAIL_ADDEDCOMMENT', 'added-comment');

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class PoP_AddComments_EmailSender_Hooks
{
    public function __construct()
    {

        //----------------------------------------------------------------------
        // Functional emails
        //----------------------------------------------------------------------
        HooksAPIFacade::getInstance()->addAction(
            'popcms:insertComment',
            array($this, 'sendemailToUsersFromComment'),
            10,
            2
        );
    }

    /**
     * Send Email when adding comments
     */
    public function sendemailToUsersFromComment($comment_id, $comment)
    {

        // If it is a trackback or a pingback, then do nothing
        // Only for published comments
        $skip = array(
            'pingback',
            'trackback'
        );
        $cmscommentsresolver = \PoPSchema\Comments\ObjectPropertyResolverFactory::getInstance();
        if (in_array($cmscommentsresolver->getCommentType($comment), $skip) || !$cmscommentsresolver->isCommentApproved($comment)) {
            return;
        }

        $cmscommentsapi = \PoPSchema\Comments\FunctionAPIFactory::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();

        $post_id = $cmscommentsresolver->getCommentPostId($comment);
        $title = $customPostTypeAPI->getTitle($post_id);
        $intro = $cmscommentsresolver->getCommentParent($comment) ?
            TranslationAPIFacade::getInstance()->__('<p>There is a response to a comment from <a href="%s">%s</a>:</p>', 'pop-emailsender') :
            TranslationAPIFacade::getInstance()->__('<p>A new comment has been added to <a href="%s">%s</a>:</p>', 'pop-emailsender');
        $content = sprintf(
            $intro,
            $customPostTypeAPI->getPermalink($post_id),
            $title
        );
        $content .= PoP_EmailTemplatesFactory::getInstance()->getCommentcontenthtml($comment);

        // Possibly the title has html entities, these must be transformed again for the subjects below
        $title = html_entity_decode($title);

        // If this comment is a response, notify the original comment's author
        // Unless they are the same person
        if ($comment_parent_id = $cmscommentsresolver->getCommentParent($comment)) {
            $comment_parent = $cmscommentsapi->getComment($comment_parent_id);
            if ($cmscommentsresolver->getCommentUserId($comment_parent) && $cmscommentsresolver->getCommentUserId($comment_parent) != $cmscommentsresolver->getCommentUserId($comment)) {
                $subject = sprintf(
                    TranslationAPIFacade::getInstance()->__('%s replied your comment in “%s”', 'pop-emailsender'),
                    $cmscommentsresolver->getCommentAuthor($comment),
                    $title
                );
                PoP_EmailSender_Utils::sendemailToUsers(array($cmscommentsresolver->getCommentAuthorEmail($comment_parent)), array($cmscommentsresolver->getCommentAuthor($comment_parent)), $subject, $content);

                // Add the users to the list of users who got an email sent to
                PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_ADDEDCOMMENT, array($cmscommentsresolver->getCommentUserId($comment_parent)));
            }
        }

        // Send an email to:
        // 1. Owner(s) of the post
        $post_ids = array(
            $post_id
        );
        // 2. Owner(s) of referenced posts
        if ($references = \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($post_id, GD_METAKEY_POST_REFERENCES)) {
            $post_ids = array_merge(
                $post_ids,
                $references
            );
        }
        // 3. Owner(s) of referencing posts
        if (defined('POP_RELATEDPOSTS_INITIALIZED')) {
            if ($referencedby = PoP_RelatedPosts_SectionUtils::getReferencedby($post_id)) {
                $post_ids = array_merge(
                    $post_ids,
                    $referencedby
                );
            }
        }

        $exclude_authors = PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_ADDEDCOMMENT);

        // Do not send the email to the author of the comment
        $exclude_authors[] = $cmscommentsresolver->getCommentUserId($comment);

        // Do not send the email to the author of the comment_parent comment, since we already sent it above
        if ($cmscommentsresolver->getCommentParent($comment)) {
            $exclude_authors[] = $cmscommentsresolver->getCommentUserId($comment_parent);
        }
        $subject = sprintf(
            TranslationAPIFacade::getInstance()->__('New comment added in “%s”', 'pop-emailsender'),
            $title
        );

        $authors = PoP_EmailSender_Utils::sendemailToUsersFromPost($post_ids, $subject, $content, $exclude_authors);

        // Add the users to the list of users who got an email sent to
        PoP_EmailSender_SentEmailsManager::getSentemailUsers(POP_EMAIL_ADDEDCOMMENT, $authors);
    }
}

/**
 * Initialization
 */
new PoP_AddComments_EmailSender_Hooks();
