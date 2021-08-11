<?php

declare(strict_types=1);

namespace PoP\Engine\CMS;

class CMSHelperService implements CMSHelperServiceInterface
{
    public function __construct(
        protected CMSServiceInterface $cmsService
    ) {
    }

    public function getLocalURLPath(string $url): string
    {
        return substr(
            $url,
            strlen($this->cmsService->getHomeURL())
        );
    }
}
