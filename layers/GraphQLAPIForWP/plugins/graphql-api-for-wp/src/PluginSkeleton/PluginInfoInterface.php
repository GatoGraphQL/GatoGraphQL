<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

interface PluginInfoInterface
{
    public function get(string $key): mixed;
}
