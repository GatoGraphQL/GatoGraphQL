<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeAPIs;

use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

abstract class AbstractUserTypeAPI implements UserTypeAPIInterface
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected CMSHelperServiceInterface $cmsHelperService,
        protected QueriedObjectHelperServiceInterface $queriedObjectHelperService,
    ) {
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
