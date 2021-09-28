<?php

declare(strict_types=1);

namespace PoP\Engine\CMS;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Misc\GeneralUtils;

class CMSHelperService implements CMSHelperServiceInterface
{
    protected CMSServiceInterface $cmsService;

    #[Required]
    public function autowireCMSHelperService(CMSServiceInterface $cmsService)
    {
        $this->cmsService = $cmsService;
    }

    public function getLocalURLPath(string $url): string | false
    {
        if (str_starts_with($url, $this->cmsService->getHomeURL())) {
            return GeneralUtils::getPath($url);
        }
        return false;
    }
}
