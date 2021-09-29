<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;

abstract class AbstractCustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    protected HooksAPIInterface $hooksAPI;
    protected CMSHelperServiceInterface $cmsHelperService;

    #[Required]
    public function autowireAbstractCustomPostTypeAPI(HooksAPIInterface $hooksAPI, CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->hooksAPI = $hooksAPI;
        $this->cmsHelperService = $cmsHelperService;
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
