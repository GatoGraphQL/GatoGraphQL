<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObjectWP\Routing;

use PoP\RootWP\Routing\RoutingManagerTrait;
use PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
use WP_Query;

class CMSRoutingStateService implements CMSRoutingStateServiceInterface
{
    use RoutingManagerTrait;

    public function getQueriedObject(): ?object
    {
        $this->init();

        if ($this->isGeneric()) {
            return null;
        }

        /** @var WP_Query */
        $query = $this->query;

        if (
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

    public function getQueriedObjectID(): string|int|null
    {
        $this->init();

        if ($this->isGeneric()) {
            return null;
        }

        /** @var WP_Query */
        $query = $this->query;

        if (
            $query->is_tag() ||
            $query->is_page() ||
            $query->is_single() ||
            $query->is_author() ||
            $query->is_category()
        ) {
            return $query->get_queried_object_id();
        }

        return null;
    }
}
