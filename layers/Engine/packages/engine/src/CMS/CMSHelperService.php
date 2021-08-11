<?php

declare(strict_types=1);

namespace PoP\Engine\CMS;

use PoP\ComponentModel\Misc\GeneralUtils;

class CMSHelperService implements CMSHelperServiceInterface
{
    public function __construct(
        protected CMSServiceInterface $cmsService
    ) {
    }

    public function getLocalURLPath(string $url): string | false
    {
        if (str_starts_with($url, $this->cmsService->getHomeURL())) {
            return GeneralUtils::getPath($url);
        }
        return false;
    }
}
