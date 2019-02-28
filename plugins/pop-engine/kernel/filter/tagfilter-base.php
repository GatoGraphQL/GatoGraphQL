<?php
namespace PoP\Engine;

abstract class TagFilterBase extends FilterBase
{
    public function getFilterArgsOverrideValues()
    {
        $args = parent::getFilterArgsOverrideValues();

        if (!$this->getFiltercomponents()) {
            return $args;
        }
        
        // Tag Search (for function getTags: https://codex.wordpress.org/Function_Reference/getTags)
        if ($search = $this->getSearch()) {
            $args['search'] = $search;
        }

        // Order / Orderby
        if ($order = $this->getOrder()) {
            $args['orderby'] = $order['orderby'];
            $args['order'] = $order['order'];
        }
        
        return $args;
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
            if ($maybe_search = $filtercomponent->getSearch($this)) {
                $search = $maybe_search;
                
                // Only 1 filter can do the Search, so already break
                break;
            }
        }
        
        return $search;
    }
}
