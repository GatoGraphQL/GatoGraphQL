<?php
namespace PoP\QueriedObject\WP;

class WPCMSRoutingState extends \PoP\QueriedObject\CMSRoutingStateBase
{
    use \PoP\CMS\WP\WPCMSRoutingStateTrait;

    public function getQueriedObject()
    {
        $this->init();
        if ($this->isStandard()) {
            return null;
        } elseif (
            $this->query->is_tag() ||
            $this->query->is_page() ||
            $this->query->is_single() ||
            $this->query->is_author() ||
            $this->query->is_category()
        ) {

            return $this->query->get_queried_object();
        }

        return null;
    }
    public function getQueriedObjectId()
    {
        $this->init();
        if ($this->isStandard()) {
            return null;
        } elseif (
            $this->query->is_tag() ||
            $this->query->is_page() ||
            $this->query->is_single() ||
            $this->query->is_author() ||
            $this->query->is_category()
        ) {

            return $this->query->get_queried_object_id();
        }

        return null;
    }
}

/**
 * Initialize
 */
new WPCMSRoutingState();
