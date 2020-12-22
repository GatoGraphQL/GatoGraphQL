<?php
namespace PoPSchema\QueriedObject\WP;

use \WP_Query;
use PoP\RoutingWP\RoutingManagerTrait;

class WPCMSRoutingState extends \PoPSchema\QueriedObject\AbstractCMSRoutingState
{
    use RoutingManagerTrait;

    public function getQueriedObject()
    {
        $this->init();
        /** @var WP_Query */
        $query = $this->query;
        if ($this->isStandard()) {
            return null;
        } elseif (
            $query->is_tag() ||
            $query->is_page() ||
            $query->is_single() ||
            $query->is_author() ||
            $query->is_category()
        ) {

            return $query->get_queried_object();
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
