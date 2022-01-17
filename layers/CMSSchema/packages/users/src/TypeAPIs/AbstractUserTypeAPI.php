<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;

abstract class AbstractUserTypeAPI implements UserTypeAPIInterface
{
    use BasicServiceTrait;

    private ?CMSHelperServiceInterface $cmsHelperService = null;

    final public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }

    public function getUserURLPath(string | int | object $userObjectOrID): ?string
    {
        $userURL = $this->getUserURL($userObjectOrID);
        if ($userURL === null) {
            return null;
        }

        /** @var string */
        return $this->getCMSHelperService()->getLocalURLPath($userURL);
    }
}
