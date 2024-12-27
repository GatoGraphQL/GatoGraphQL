<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeAPIs;

use PoP\Root\Services\AbstractBasicService;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;

abstract class AbstractCustomPostTypeAPI extends AbstractBasicService implements CustomPostTypeAPIInterface
{
    private ?CMSHelperServiceInterface $cmsHelperService = null;

    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        if ($this->cmsHelperService === null) {
            /** @var CMSHelperServiceInterface */
            $cmsHelperService = $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
            $this->cmsHelperService = $cmsHelperService;
        }
        return $this->cmsHelperService;
    }

    public function getPermalinkPath(string|int|object $customPostObjectOrID): ?string
    {
        $permalink = $this->getPermalink($customPostObjectOrID);
        if ($permalink === null) {
            return null;
        }
        return $this->getCMSHelperService()->getLocalURLPath($permalink);
    }
}
