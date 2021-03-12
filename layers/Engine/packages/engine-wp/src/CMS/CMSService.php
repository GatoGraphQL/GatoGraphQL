<?php

declare(strict_types=1);

namespace PoP\EngineWP\CMS;

use PoP\Engine\CMS\CMSServiceInterface;

class CMSService implements CMSServiceInterface
{
    /**
     * @return mixed
     */
    public function getOption(string $option, $default = false)
    {
        return get_option($option, $default);
    }
}
