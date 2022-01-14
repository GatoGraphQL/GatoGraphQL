<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Routing;

/**
 * Default "mock" service returning always a void response.
 * It must be overriden for the specific CMS.
 *
 * This service is already injected to make PHPUnit work.
 */
class VoidCMSRoutingStateService implements CMSRoutingStateServiceInterface
{
    public function getQueriedObject(): ?object
    {
        return null;
    }

    public function getQueriedObjectId(): string | int | null
    {
        return null;
    }
}
