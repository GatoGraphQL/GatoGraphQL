<?php
namespace PoP\Engine;

abstract class PostFilterBase extends FilterBase
{
    public function getFilterArgsOverrideValues()
    {
        $args = parent::getFilterArgsOverrideValues();

        if (!$this->getFiltercomponents()) {
            return $args;
        }
        
        // Post status
        if ($status = $this->getPoststatus()) {
            $args['post_status'] = $status;
        }
        
        // Search
        if ($search = $this->getSearch()) {
            $args['is_search'] = true;
            $args['s'] = $search;
        }

        // Tags
        if ($tags = $this->getTags()) {
            $args['tag'] = implode(',', $tags);
        }

        // Categories
        if ($categories = $this->getCategories()) {
            $args['cat'] = implode(',', $categories);
        }

        // Taxonomies
        if ($taxonomyterms = $this->getTaxonomies()) {
            // The format of the taxonomies now is array($taxonomy => $terms)
            // Convert it to an array like this: array('taxonomy' => $taxonomy, 'terms' => $terms)
            $taxqueries = array();
            foreach ($taxonomyterms as $taxonomy => $terms) {
                $taxqueries[] = array(
                    'taxonomy' => $taxonomy,
                    'terms' => $terms,
                );
            }
            $args['tax_query'] = array_merge(
                array(
                    'relation' => 'OR'
                ),
                $taxqueries
            );
        }

        // Order / Orderby
        if ($order = $this->getOrder()) {
            $args['orderby'] = $order['orderby'];
            $args['order'] = $order['order'];
        }

        // Author
        if ($author = $this->getAuthor()) {
            $args['author'] = implode(",", $author);
        }
        
        return $args;
    }

    public function getFilterArgs()
    {
        $args = parent::getFilterArgs();
        
        if (!$this->getFiltercomponents()) {
            return $args;
        }
                                
        // Meta query
        if ($meta_query = $this->getMetaquery()) {
            $args['meta_query'] = $meta_query;
        }
        
        return $args;
    }
    
    public function getMetaquery()
    {
        $meta_query = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($filtercomponent_metaquery = $filtercomponent->getMetaquery($this)) {
                $meta_query = array_merge($meta_query, $filtercomponent_metaquery);
            }
        }
        
        if ($meta_query) {
            // When filtering users, it will bring them duplicated. Solution: hook "foundUsersQueryAvoidDuplicates" in users.php
            $meta_query['relation'] = 'AND';
        }
        return $meta_query;
    }
    
    public function getAuthor()
    {
        $author = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($filtercomponent_author = $filtercomponent->getAuthor($this)) {
                $author = array_merge($author, $filtercomponent_author);
            }
        }
        
        return $author;
    }

    public function getPoststatus()
    {
        $status = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($filtercomponent_poststatus = $filtercomponent->getPoststatus($this)) {
                $status = array_merge($status, $filtercomponent_poststatus);
            }
        }
        
        return $status;
    }

    public function getCategories()
    {
        $categories = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($filtercomponent_categories = $filtercomponent->getCategories($this)) {
                $categories = array_merge($categories, $filtercomponent_categories);
            }
        }
        
        return $categories;
    }

    public function getTaxonomies()
    {
        $taxonomies = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            // array_merge_recursive: it allows the terms to be merged together into a single array under the same taxonomy
            // Eg: if we get array('category' => array(1, 3, 4)) and array('category' => array(2, 5)), the result will be array('category' => array(1, 2, 3, 4, 5))
            $taxonomies = array_merge_recursive($taxonomies, $filtercomponent->getTaxonomies($this));
        }
        
        return $taxonomies;
    }

    public function getTags()
    {
        $tags = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($filtercomponent_tags = $filtercomponent->getTags($this)) {
                $tags = array_merge($tags, $filtercomponent_tags);
            }
        }
        
        return $tags;
    }

    public function getOrder()
    {
        $order = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($order = $filtercomponent->getOrder($this)) {
                // Only 1 filter can define the Order, so already break
                break;
            }
        }
        
        return $order;
    }
    
    public function getSearch()
    {
        $search = '';
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($search = $filtercomponent->getSearch($this)) {
                // Only 1 filter can do the Search, so already break
                break;
            }
        }
        
        return $search;
    }
    
    public function getPostdates()
    {
        $postdates = array();
        foreach ($this->getFiltercomponents() as $filtercomponent) {
            if ($postdates = $filtercomponent->getPostdates($this)) {
                // Only 1 filter can define the post dates, so already break
                break;
            }
        }

        return $postdates;
    }

    public function filterWhere($where = '')
    {
        $postdates = $this->getPostdates();

        if ($postdates['from']) {
            $where .= " AND post_date >= '".$postdates['from']."'";
        }

        if ($postdates['to']) {
            $where .= " AND post_date < '".$postdates['to']."'";
        }

        return $where;
    }
}
