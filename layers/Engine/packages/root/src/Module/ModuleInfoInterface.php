<?php

declare(strict_types=1);

namespace PoP\Root\Module;

interface ModuleInfoInterface
{
    public function get(string $key): mixed;
}
