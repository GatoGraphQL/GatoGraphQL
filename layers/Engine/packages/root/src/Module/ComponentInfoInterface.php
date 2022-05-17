<?php

declare(strict_types=1);

namespace PoP\Root\Module;

interface ComponentInfoInterface
{
    public function get(string $key): mixed;
}
