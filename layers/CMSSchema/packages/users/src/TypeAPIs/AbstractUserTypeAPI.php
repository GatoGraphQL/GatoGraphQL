<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;

abstract class AbstractUserTypeAPI implements UserTypeAPIInterface
{
    use BasicServiceTrait;

    private ?CMSHelperServiceInterface $cmsHelperService = null;

    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        if ($this->cmsHelperService === null) {
            /** @var CMSHelperServiceInterface */
            $cmsHelperService = $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
            $this->cmsHelperService = $cmsHelperService;
        }
        return $this->cmsHelperService;
    }

    public function getUserURLPath(string|int|object $userObjectOrID): ?string
    {
        $userURL = $this->getUserURL($userObjectOrID);
        if ($userURL === null) {
            return null;
        }
        return $this->getCMSHelperService()->getLocalURLPath($userURL);
    }
}
