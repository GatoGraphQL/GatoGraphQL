<?php

class PoP_PPP_PreviewContent_FunctionsAPI extends PoP_PreviewContent_FunctionsAPI_Base implements PoP_PreviewContent_FunctionsAPI
{
    public function getPreviewLink($post_id)
    {
        return gdPppPreviewLink($post_id);
    }

    public function setPreview($post_id)
    {
        $preview_post_ids = DS_Public_Post_Preview::get_preview_post_ids();
        if (!in_array($post_id, $preview_post_ids)) {
            $preview_post_ids[] = $post_id;
            DS_Public_Post_Preview::set_preview_post_ids($preview_post_ids);
        }
    }

    public function removePreview($post_id)
    {
        $preview_post_ids = DS_Public_Post_Preview::get_preview_post_ids();
        if (in_array($post_id, $preview_post_ids)) {
            array_splice($preview_post_ids, array_search($post_id, $preview_post_ids), 1);
            DS_Public_Post_Preview::set_preview_post_ids($preview_post_ids);
        }
    }
}

/**
 * Initialize
 */
new PoP_PPP_PreviewContent_FunctionsAPI();
