<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;

abstract class AbstractCustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    public function __construct(
        protected QueriedObjectHelperServiceInterface $queriedObjectHelperService
    ) {
    }

    public function getPermalinkPath(string | int | object $customPostObjectOrID): ?string
    {
        $permalink = $this->getPermalink($customPostObjectOrID);
        if ($permalink === null) {
            return null;
        }

        return $this->queriedObjectHelperService->getURLPath($permalink);
    }
}
