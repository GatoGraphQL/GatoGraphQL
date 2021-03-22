<?php

declare(strict_types=1);

namespace PoP\Engine\CMS;

interface CMSServiceInterface
{
    public function getOption(string $option, mixed $default = false): mixed;
}
