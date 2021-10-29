<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeAPIs;

use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserTypeAPI implements UserTypeAPIInterface
{
    private ?HooksAPIInterface $hooksAPI = null;
    private ?CMSHelperServiceInterface $cmsHelperService = null;

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
    }
    public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }

    //#[Required]
    final public function autowireAbstractUserTypeAPI(HooksAPIInterface $hooksAPI, CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->hooksAPI = $hooksAPI;
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
