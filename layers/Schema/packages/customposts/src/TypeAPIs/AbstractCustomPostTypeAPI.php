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

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
    }
    public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }

    //#[Required]
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
