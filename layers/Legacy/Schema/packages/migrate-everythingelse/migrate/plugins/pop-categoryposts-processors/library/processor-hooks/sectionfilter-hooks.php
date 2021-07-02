<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;

class PoPTheme_CategoryProcessors_SectionFilterHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'wassup_section_taxonomyterms',
            array($this, 'getTaxonomyterms')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'wassup_contentpostsection_cats',
            array($this, 'getCats')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'GD_FormInput_ContentSections:taxonomyterms:name',
            array($this, 'getTaxonomytermsName'),
            10,
            3
        );
        HooksAPIFacade::getInstance()->addFilter(
            'GD_FormInput_PostSections:cat:name',
            array($this, 'getCatName'),
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
