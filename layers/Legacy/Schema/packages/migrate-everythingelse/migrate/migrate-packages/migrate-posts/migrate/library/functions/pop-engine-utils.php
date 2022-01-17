<?php
namespace PoPCMSSchema\Posts;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class Engine_Utils
{
    public static function getCustomPostPath($post_id, $remove_post_slug = false)
    {
        $cmsService = CMSServiceFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();

        // Generate the post path. If the post is published, just use permalink. If not, we can't, or it will get the URL to edit the post,
        // and not the URL the post will be published to, which is what is needed by the ResourceLoader
        $permalink = $customPostTypeAPI->getPermalink($post_id);
        $domain = GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL());

        // Remove the domain from the permalink => page path
        $post_path = substr($permalink, strlen($domain));

        // Remove the post slug
        if ($remove_post_slug) {
            $post_slug = $customPostTypeAPI->getSlug($post_id);
            $post_path = substr($post_path, 0, strlen($post_path) - strlen(GeneralUtils::maybeAddTrailingSlash($post_slug)));
        }

        return $post_path;
    }
}
