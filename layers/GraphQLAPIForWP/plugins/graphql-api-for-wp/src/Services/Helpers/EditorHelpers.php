<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

class EditorHelpers
{
    /**
     * Get the post type currently being created/edited in the editor
     *
     * phpcs:disable Generic.PHP.DisallowRequestSuperglobal
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
     * Get the post ID currently being edited in the editor
     */
    public function getEditingPostID(): ?int
    {
        if (!\is_admin()) {
            return null;
        }
        global $pagenow;
        if ($pagenow != 'post.php') {
            return null;
        }
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
