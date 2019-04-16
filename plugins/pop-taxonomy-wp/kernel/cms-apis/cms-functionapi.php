<?php
namespace PoP\Taxonomy\WP;

class FunctionAPI extends \PoP\Taxonomy\FunctionAPI_Base implements \PoP\Taxonomy\FunctionAPI
{
    public function getPostTypeTaxonomies($post_type)
    {
    	return get_object_taxonomies($post_type);
    }
    public function isTaxonomyHierarchical($taxonomy)
    {
    	$taxonomy_object = get_taxonomy($taxonomy);
    	return $taxonomy_object->hierarchical;
    }
    public function getTaxonomyTerms($taxonomy, $options = [])
    {
    	$query = [
            'taxonomy' => $taxonomy,
        ];
        $return_type = $options['return-type'];
        if ($return_type == POP_RETURNTYPE_IDS) {
        	$query['fields'] = 'ids';
        }
    	return get_terms($query);
    }

    protected function returnCategoryObjectsOrIDs($categories, $options = [])
    {
        $return_type = $options['return-type'];
        if ($return_type == POP_RETURNTYPE_IDS) {
            return array_map(function($category) {
                return $category->term_id;
            }, $categories);
        }

        return $categories;
    }
    public function getCategories($query, $options = [])
    {
        if (isset($query['hide-empty'])) {
            $query['hide_empty'] = $query['hide-empty'];
            unset($query['hide-empty']);
        }
        return $this->returnCategoryObjectsOrIDs(get_categories($query));
    }

    // public function getPostCategories($post_id, $options = [])
    // {
    //     return $this->returnCategoryObjectsOrIDs(get_the_category($post_id));
    // }
    // public function getTheCategory($post_id)
    // {
    //     return get_the_category($post_id);
    // }
    public function getPostCategories($post_id, array $options = [])
    {
        $query = [];
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ids';
            }
            elseif ($return_type == POP_RETURNTYPE_SLUGS) {
                $query['fields'] = 'slugs';
            }
        }
        return wp_get_post_categories($post_id, $query);
    }
    public function getPostTags($post_id, array $options = [])
    {
        $query = [];
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ids';
            }
            elseif ($return_type == POP_RETURNTYPE_NAMES) {
                $query['fields'] = 'names';
            }
        }
        return wp_get_post_tags($post_id, $query);
    }
    // public function getObjectTerms($object_ids, $taxonomies, $args = array())
    // {
    //     return wp_get_object_terms($object_ids, $taxonomies, $args);
    // }
    public function getPostTaxonomyTerms($post_id, $taxonomy, $options = [])
    {
        if ($terms = get_the_terms($post_id, $taxonomy)) {
            if ($return_type = $options['return-type']) {
                if ($return_type == POP_RETURNTYPE_IDS) {
                    return array_map(function($term_object) {
                        return $term_object->term_id;
                    }, $terms);
                }
                elseif ($return_type == POP_RETURNTYPE_SLUGS) {
                    return array_map(function($term_object) {
                        return $term_object->slug;
                    }, $terms);
                }
            }
            return $terms;
        }
        return [];
    }
    // public function getCategoryTitle($cat)
    // {
    //     // Copied from `single_term_title` in wp-includes/general-template.php
    //     return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('single_cat_title', $cat->name);
    // }
    // public function getTagTitle($tag)
    // {
    //     // Copied from `single_term_title` in wp-includes/general-template.php
    //     return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('single_tag_title', $tag->name);
    // }
    public function getTagName($tag_id)
    {
        $tag = get_tag($tag_id);
        return $tag->name;
    }
    // public function getQueryVar($var, $default = '')
    // {
    //     return get_query_var($var, $default);
    // }
    // public function getCategoryIds($args = array())
    // {
    //     $args = array_merge(
    //         array(
    //             'taxonomy' => 'category',
    //             'hide_empty' => false,
    //             'fields' => 'ids',
    //         ),
    //         $args
    //     );
    //     return get_terms($args);
    // }
    public function getCategoryName($cat_id)
    {
        return get_cat_name($cat_id);
    }
    public function getCategoryParent($cat_id)
    {
        $category = get_category($cat_id);
        return $category->parent;
    }
    public function getCategorySlug($cat_id)
    {
        $category = get_category($cat_id);
        return $category->slug;
    }

    public function getCategoryPath($category_id)
    {
        $taxonomy = 'category';
        
        // Convert it to int, otherwise it thinks it's a string and the method below fails
        $category_path = get_term_link((int) $category_id, $taxonomy);

        // Remove the initial part ("https://www.mesym.com/en/categories/")
        global $wp_rewrite;
        $termlink = $wp_rewrite->get_extra_permastruct($taxonomy);
        $termlink = str_replace("%$taxonomy%", '', $termlink);
        $termlink = $this->getHomeURL(user_trailingslashit($termlink, $taxonomy));

        return substr($category_path, strlen($termlink));
    }
    public function getTag($tag_id)
    {
        return get_tag($tag_id);
    }
    public function getTagByName($tag_name)
    {
        return get_term_by('name', $tag_name, 'post_tag');
    }
    public function getTags($query, array $options = [])
    {
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ids';
            }
        }

        // Convert the parameters
        if ($query['include']) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        // if (isset($query['fields'])) {
        //     // Same param name, so do nothing
        // }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        // if (isset($query['number'])) {
        //     // Same param name, so do nothing
        // }
        if (isset($query['limit'])) {
            
            // To bring all results, get_tags needs "number => 0" instead of -1
            $query['number'] = ($query['limit'] == -1) ? 0 : $query['limit'];
            unset($query['limit']);
        }
        if (isset($query['search'])) {
            // Same param name, so do nothing
        }
        // if (isset($query['meta-query']) ){ 
            
        //     $query['meta_query'] = $query['meta-query'];
        //     unset($query['meta-query']);
        // }
        if (isset($query['slugs']) ){ 
            
            $query['slug'] = $query['slugs'];
            unset($query['slugs']);
        }

        $query = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'CMSAPI:tags:query',
            $query
        );
        return get_tags($query);
    }
    public function getTagLink($tag_id)
    {
        return get_tag_link($tag_id);
    }
    public function getTagBase()
    {
        return $this->getOption('tag_base');
    }

    public function setPostTags($post_id, array $tags, bool $append = false)
    {
        return wp_set_post_tags($post_id, $tags, $append);
    }

    public function setPostTerms($post_id, $tags, $taxonomy, $append = false)
    {
        return wp_set_post_terms($post_id, $tags, $taxonomy, $append);
    }

    public function getTermLink($term_id)
    {
        return get_term_link($term_id);
    }

    public function getTermName($term_id, $taxonomy)
    {
        $term = get_term($term_id, $taxonomy);
        return $term->name;
    }

    public function hasCategory($cat_id, $post_id)
    {
        return has_category($cat_id, $post_id);
    }

    public function hasTerm($term_id, $taxonomy, $post_id)
    {
        return has_term($term_id, $taxonomy, $post_id);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
