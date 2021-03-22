<?php

declare(strict_types=1);

namespace PoP\EngineWP\CMS;

use PoP\Engine\CMS\CMSServiceInterface;

class CMSService implements CMSServiceInterface
{
    public function getOption(string $option, mixed $default = false): mixed
    {
        return get_option($option, $default);
    }
}
