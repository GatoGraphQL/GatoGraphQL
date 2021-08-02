<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use PoP\Engine\CMS\CMSServiceInterface;

abstract class AbstractCustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    public function __construct(
        protected CMSServiceInterface $cmsService
    ) {
    }

    public function getPermalinkPath(string | int | object $customPostObjectOrID): ?string
    {
        $permalink = $this->getPermalink($customPostObjectOrID);
        if ($permalink === null) {
            return null;
        }
        
        // Remove the Home URL from the permalink
        return substr(
            $permalink,
            strlen($this->cmsService->getHomeURL())
        );
    }
}
