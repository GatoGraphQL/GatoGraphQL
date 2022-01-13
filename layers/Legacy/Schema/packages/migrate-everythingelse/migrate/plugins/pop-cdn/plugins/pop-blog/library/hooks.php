<?php
use PoP\Routing\URLParams;
use PoPSchema\PostTags\ComponentConfiguration as PostTagsComponentConfiguration;
use PoPSchema\Users\ComponentConfiguration as UsersComponentConfiguration;

class PoP_CDN_Blog_CDNHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'getThumbprintPartialpaths'),
            10,
            2
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:hasParamValues',
            array($this, 'getThumbprintParamvalues'),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {

        // Trending Tags: added also dependency on POST and COMMENT (apart from TAG),
        // because a trending tag may not be newly created to become trending, so TAG alone doesn't work

        $routes = array();
        if ($thumbprint == POP_CDN_THUMBPRINT_USER) {
            $routes = array_filter(
                array(
                    POP_BLOG_ROUTE_SEARCHCONTENT,
                    POP_BLOG_ROUTE_SEARCHUSERS,
                    POP_BLOG_ROUTE_CONTENT,
                    UsersComponentConfiguration::getUsersRoute(),
                    POP_BLOG_ROUTE_COMMENTS,
                )
            );
        } elseif ($thumbprint == POP_CDN_THUMBPRINT_POST) {
            $routes = array_filter(
                array(
                    POP_BLOG_ROUTE_SEARCHCONTENT,
                    POP_BLOG_ROUTE_CONTENT,
                )
            );
        } elseif ($thumbprint == POP_CDN_THUMBPRINT_COMMENT) {
            $routes = array_filter(
                array(
                    POP_BLOG_ROUTE_COMMENTS,
                )
            );
        } elseif ($thumbprint == POP_CDN_THUMBPRINT_TAG) {
            $routes = array_filter(
                array(
                    PostTagsComponentConfiguration::getPostTagsRoute(),
                )
            );
        }

        // Add the values to the configuration
        foreach ($routes as $route) {
            $paths[] = $route.'/';
        }

        return $paths;
    }

    public function getThumbprintParamvalues($paramvalues, $thumbprint)
    {
        if ($thumbprint == POP_CDN_THUMBPRINT_COMMENT) {
            // For the "comments" tab in a single post:
            // eg: getpop.org/en/posts/some-post-title/?tab=comments
            $routes = array_filter(
                array(
                    POP_BLOG_ROUTE_COMMENTS,
                )
            );
            foreach ($routes as $route) {
                $paramvalues[] = array(
                    URLParams::ROUTE,
                    $route
                );
            }
        }

        return $paramvalues;
    }
}

/**
 * Initialize
 */
new PoP_CDN_Blog_CDNHooks();
