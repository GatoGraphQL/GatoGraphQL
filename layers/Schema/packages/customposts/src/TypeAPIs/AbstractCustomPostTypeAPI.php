<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;

abstract class AbstractCustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected CMSHelperServiceInterface $cmsHelperService,
        protected QueriedObjectHelperServiceInterface $queriedObjectHelperService,
    ) {
    }

    public function getPermalinkPath(string | int | object $customPostObjectOrID): ?string
    {
        $permalink = $this->getPermalink($customPostObjectOrID);
        if ($permalink === null) {
            return null;
        }

        /** @var string */
        return $this->cmsHelperService->getLocalURLPath($permalink);
    }
}
