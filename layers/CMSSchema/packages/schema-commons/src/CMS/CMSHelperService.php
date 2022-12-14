<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\CMS;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\BasicServiceTrait;

class CMSHelperService implements CMSHelperServiceInterface
{
    use BasicServiceTrait;

    private ?CMSServiceInterface $cmsService = null;

    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        /** @var CMSServiceInterface */
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }

    public function getLocalURLPath(string $url): ?string
    {
        if (str_starts_with($url, $this->getCMSService()->getHomeURL())) {
            return GeneralUtils::getPath($url);
        }
        return null;
    }
}
