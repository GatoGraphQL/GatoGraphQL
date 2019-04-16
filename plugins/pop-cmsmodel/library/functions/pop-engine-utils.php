<?php
namespace PoP\CMSModel;

class Engine_Utils
{
    public static function getPostPath($post_id, $remove_post_slug = false)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $cmshelpers = \PoP\CMS\HelperAPI_Factory::getInstance();

        // Generate the post path. If the post is published, just use permalink. If not, we can't, or it will get the URL to edit the post,
        // and not the URL the post will be published to, which is what is needed by the ResourceLoader
        $permalink = $cmsapi->getPermalink($post_id);
        $domain = $cmshelpers->maybeAddTrailingSlash($cmsapi->getHomeURL());

        // Remove the domain from the permalink => page path
        $post_path = substr($permalink, strlen($domain));

        // Remove the post slug
        if ($remove_post_slug) {
            $post_slug = $cmsapi->getPostSlug($post_id);
            $post_path = substr($post_path, 0, strlen($post_path) - strlen($cmshelpers->maybeAddTrailingSlash($post_slug)));
        }

        return $post_path;
    }
}
