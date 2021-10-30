<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostTypeAPI implements CustomPostTypeAPIInterface
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

    public function getPermalinkPath(string | int | object $customPostObjectOrID): ?string
    {
        $permalink = $this->getPermalink($customPostObjectOrID);
        if ($permalink === null) {
            return null;
        }

        /** @var string */
        return $this->getCmsHelperService()->getLocalURLPath($permalink);
    }
}
