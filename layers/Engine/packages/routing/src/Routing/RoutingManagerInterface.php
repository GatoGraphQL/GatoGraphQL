<?php

declare(strict_types=1);

namespace PoP\Routing;

interface RoutingManagerInterface
{
    public function getCurrentRequestNature(): string;
    public function getCurrentRoute(): string;
}
