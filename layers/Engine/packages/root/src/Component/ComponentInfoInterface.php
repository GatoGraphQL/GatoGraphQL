<?php

declare(strict_types=1);

namespace PoP\Root\Component;

interface ComponentInfoInterface
{
    public function get(string $key): mixed;
}
