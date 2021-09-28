<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeAPIs;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

abstract class AbstractUserTypeAPI implements UserTypeAPIInterface
{
    protected HooksAPIInterface $hooksAPI;
    protected CMSHelperServiceInterface $cmsHelperService;

    #[Required]
    public function autowireAbstractUserTypeAPI(HooksAPIInterface $hooksAPI, CMSHelperServiceInterface $cmsHelperService)
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
        return $this->cmsHelperService->getLocalURLPath($userURL);
    }
}
