<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use PoP\Engine\CMS\CMSHelperServiceInterface;

abstract class AbstractCustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    public function __construct(
        protected CMSHelperServiceInterface $cmsHelperService,
    ) {
    }

    public function getPermalinkPath(string | int | object $customPostObjectOrID): ?string
    {
        $permalink = $this->getPermalink($customPostObjectOrID);
        if ($permalink === null) {
            return null;
        }

        return $this->CMSHelperService->getURLPath($permalink);
    }
}
