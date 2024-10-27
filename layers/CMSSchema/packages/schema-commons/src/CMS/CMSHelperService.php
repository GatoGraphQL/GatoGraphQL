<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\CMS;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\BasicServiceTrait;

class CMSHelperService implements CMSHelperServiceInterface
{
    use BasicServiceTrait;

    private ?CMSServiceInterface $cmsService = null;

    final protected function getCMSService(): CMSServiceInterface
    {
        if ($this->cmsService === null) {
            /** @var CMSServiceInterface */
            $cmsService = $this->instanceManager->getInstance(CMSServiceInterface::class);
            $this->cmsService = $cmsService;
        }
        return $this->cmsService;
    }

    public function getLocalURLPath(string $url): ?string
    {
        if (str_starts_with($url, $this->getCMSService()->getHomeURL())) {
            return GeneralUtils::getPath($url);
        }
        return null;
    }

    /**
     * Indicate if the URL belongs to the current domain,
     * whether http or https
     */
    public function isCurrentDomain(string $url): bool
    {
        $homeURL = $this->getCMSService()->getHomeURL();

        return GeneralUtils::getHost($url) === GeneralUtils::getHost($homeURL);
    }
}
