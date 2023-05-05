<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use WP_Post;

class EditorHelpers
{
    /**
     * Get the post type currently being created/edited in the editor
     *
     * phpcs:disable Generic.PHP.DisallowRequestSuperglobal
     * phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
     */
    public function getEditingPostType(): ?string
    {
        // When in the editor, there is no direct way to obtain the post type in hook "init",
        // since $typenow has not been initialized yet
        // Hence, recreate the logic to get post type from URL if we are on post-new.php, or
        // from edited post in post.php
        if (!\is_admin()) {
            return null;
        }
        global $pagenow;
        if (!in_array($pagenow, ['post-new.php', 'post.php'])) {
            return null;
        }
        $typenow = null;
        if ('post-new.php' === $pagenow) {
            if (isset($_REQUEST['post_type']) && \post_type_exists($_REQUEST['post_type'])) {
                $typenow = $_REQUEST['post_type'];
            };
        } elseif ('post.php' === $pagenow) {
            $post_id = null;
            if (isset($_GET['post']) && isset($_POST['post_ID']) && (int) $_GET['post'] !== (int) $_POST['post_ID']) {
                // Do nothing
            } elseif (isset($_GET['post'])) {
                $post_id = (int) $_GET['post'];
            } elseif (isset($_POST['post_ID'])) {
                $post_id = (int) $_POST['post_ID'];
            }
            if (!is_null($post_id)) {
                $post = \get_post($post_id);
                if (!is_null($post)) {
                    $typenow = $post->post_type;
                }
            }
        }
        return $typenow;
    }

    /**
     * Get the post ID currently being edited in the editor,
     * whether on the Edit Post screen (post.php), or on the
     * New Post screen (post-new.php)
     *
     * phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
     */
    public function getEditingPostID(): ?int
    {
        if (!\is_admin()) {
            return null;
        }
        /** @global string */
        global $pagenow;
        if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
            return null;
        }

        /**
         * If in the New Post screen, the global $post, with status
         * "auto_draft", will already have been created.
         *
         * Retrieve and use this ID already, so that when creating
         * a Persisted Query, the GraphiQL client already has the
         * right endpoint URL, with the right configuration based
         * on the chosen Schema Configuration.
         */
        if ($pagenow === 'post-new.php') {
            /** @global WP_Post */
            global $post;
            return $post->ID;
        }

        /**
         * We are in the Edit Post screen
         */
        $post_id = null;
        if (isset($_GET['post']) && isset($_POST['post_ID']) && (int) $_GET['post'] !== (int) $_POST['post_ID']) {
            // Do nothing
        } elseif (isset($_GET['post'])) {
            $post_id = (int) $_GET['post'];
        } elseif (isset($_POST['post_ID'])) {
            $post_id = (int) $_POST['post_ID'];
        }
        return $post_id;
    }
}
