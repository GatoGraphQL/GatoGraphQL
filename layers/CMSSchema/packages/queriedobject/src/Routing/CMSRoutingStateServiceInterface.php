<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\Routing;

interface CMSRoutingStateServiceInterface
{
    /**
     * Get the currently queried object
     */
    public function getQueriedObject(): ?object;

    /**
     * Get the ID of the currently queried object
     */
    public function getQueriedObjectId(): string | int | null;
}
