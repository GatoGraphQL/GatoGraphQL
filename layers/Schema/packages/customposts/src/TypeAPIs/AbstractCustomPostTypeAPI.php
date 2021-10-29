<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostTypeAPI implements CustomPostTypeAPIInterface
{
    protected ?HooksAPIInterface $hooksAPI = null;
    protected ?CMSHelperServiceInterface $cmsHelperService = null;

    #[Required]
    final public function autowireAbstractCustomPostTypeAPI(HooksAPIInterface $hooksAPI, CMSHelperServiceInterface $cmsHelperService): void
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
        return $this->getCmsHelperService()->getLocalURLPath($permalink);
    }
}
