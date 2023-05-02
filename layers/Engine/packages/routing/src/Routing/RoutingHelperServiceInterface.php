<?php

declare(strict_types=1);

namespace PoP\Routing;

interface RoutingHelperServiceInterface
{
    public function getRequestURI(): ?string;
    public function getRequestURIPath(): ?string;
}
