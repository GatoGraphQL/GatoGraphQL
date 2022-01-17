<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommonsWP\CMS;

use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;

use function get_option;
use function get_site_url;
use function home_url;

class CMSService implements CMSServiceInterface
{
    public function getOption(string $option, mixed $default = false): mixed
    {
        return get_option($option, $default);
    }

    public function getHomeURL(string $path = ''): string
    {
        return home_url($path);
    }

    public function getSiteURL(): string
    {
        return get_site_url();
    }
}
