<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeAPIs;

use PoP\ComponentModel\TypeAPIs\InjectedFilterDataloadingModuleTypeAPITrait;
use PoP\Hooks\HooksAPIInterface;
use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use WP_User;
use WP_User_Query;

abstract class AbstractUserTypeAPI implements UserTypeAPIInterface
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected QueriedObjectHelperServiceInterface $queriedObjectHelperService,
    ) {
    }

    public function getUserURLPath(string | int | object $userObjectOrID): ?string
    {
        $userURL = $this->getUserURL($userObjectOrID);
        if ($userURL === null) {
            return null;
        }

        return $this->queriedObjectHelperService->getURLPath($userURL);
    }
}
