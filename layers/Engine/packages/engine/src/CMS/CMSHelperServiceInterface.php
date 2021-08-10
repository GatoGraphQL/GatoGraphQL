<?php

declare(strict_types=1);

namespace PoP\Engine\CMS;

interface CMSHelperServiceInterface
{
    /**
     * Remove the Home URL from the permalink
     */
    public function getURLPath(string $url): string;
}
