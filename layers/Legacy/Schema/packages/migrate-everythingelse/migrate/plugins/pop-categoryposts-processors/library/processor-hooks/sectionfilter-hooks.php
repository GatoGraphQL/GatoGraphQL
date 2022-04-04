<?php
use PoP\Engine\Route\RouteUtils;

class PoPTheme_CategoryProcessors_SectionFilterHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'wassup_section_taxonomyterms',
            $this->getTaxonomyterms(...)
        );
        \PoP\Root\App::addFilter(
            'wassup_contentpostsection_cats',
            $this->getCats(...)
        );
        \PoP\Root\App::addFilter(
            'GD_FormInput_ContentSections:taxonomyterms:name',
            $this->getTaxonomytermsName(...),
            10,
            3
        );
        \PoP\Root\App::addFilter(
            'GD_FormInput_PostSections:cat:name',
            $this->getCatName(...),
            10,
            2
        );
    }

    public function getCatName($name, $cat)
    {
        return $this->getTaxonomytermsName($name, 'category', $cat);
    }

    public function getTaxonomytermsName($name, $taxonomy, $term)
    {
        switch ($taxonomy) {
            case 'category':
                $routes = PoP_CategoryPosts_Utils::getCatRoutes();
                if ($route = $routes[$term] ?? null) {
                    return RouteUtils::getRouteTitle($route);
                }
                break;
        }

        return $name;
    }

    public function getCats($cats)
    {
        return array_merge(
            $cats,
            PoP_CategoryPosts_Utils::getCats()
        );
    }

    public function getTaxonomyterms($section_taxonomyterms)
    {
        if ($cats = PoP_CategoryPosts_Utils::getCats()) {
            $section_taxonomyterms = array_merge_recursive(
                $section_taxonomyterms,
                array(
                    'category' => $cats,
                )
            );
        }

        return $section_taxonomyterms;
    }
}

/**
 * Initialization
 */
new PoPTheme_CategoryProcessors_SectionFilterHooks();
