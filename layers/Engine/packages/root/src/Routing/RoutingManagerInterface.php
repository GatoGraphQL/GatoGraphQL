<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

interface RoutingManagerInterface
{
    /**
     * @return string[]
     */
    public function getRoutes(): array;
    public function getCurrentNature(): string;
    public function getCurrentRoute(): string;
}
