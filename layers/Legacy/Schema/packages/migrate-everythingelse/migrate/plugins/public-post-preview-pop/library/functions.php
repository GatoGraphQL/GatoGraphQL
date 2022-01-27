<?php
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

// Complement to the plugin: also save_post when in webplatform
if (!is_admin()) {
    \PoP\Root\App::addAction('save_post', array('DS_Public_Post_Preview', 'register_public_preview'), 20, 2);
}

function gdPppPreviewLink($post_id)
{

    // Check if preview enabled for this post
    $preview_post_ids = \DS_Public_Post_Preview::get_preview_post_ids();
    if (in_array($post_id, $preview_post_ids)) {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return \DS_Public_Post_Preview::get_preview_link($customPostTypeAPI->getCustomPost($post_id));
    }

    return null;
}
