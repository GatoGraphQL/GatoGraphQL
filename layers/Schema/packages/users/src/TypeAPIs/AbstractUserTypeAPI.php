<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeAPIs;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserTypeAPI implements UserTypeAPIInterface
{
    use BasicServiceTrait;

    private ?CMSHelperServiceInterface $cmsHelperService = null;

    public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }

    //#[Required]
    final public function autowireAbstractUserTypeAPI(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }

    public function getUserURLPath(string | int | object $userObjectOrID): ?string
    {
        $userURL = $this->getUserURL($userObjectOrID);
        if ($userURL === null) {
            return null;
        }

        /** @var string */
        return $this->getCmsHelperService()->getLocalURLPath($userURL);
    }
}
