<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

interface RoutingHelperServiceInterface
{
    public function getRequestURI(): string;
    public function getRequestURIPath(): string;
}
