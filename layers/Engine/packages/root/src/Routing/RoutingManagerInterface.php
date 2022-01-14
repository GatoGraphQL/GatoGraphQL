<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

interface RoutingManagerInterface
{
    public function getCurrentNature(): string;
    public function getCurrentRoute(): string;
}
