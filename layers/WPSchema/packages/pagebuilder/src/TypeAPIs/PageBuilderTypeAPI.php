<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeAPIs;

use PoPWPSchema\PageBuilder\Services\PageBuilderServiceInterface;
use PoP\Root\Services\AbstractBasicService;

class PageBuilderTypeAPI extends AbstractBasicService implements PageBuilderTypeAPIInterface
{
    private ?PageBuilderServiceInterface $pageBuilderService = null;

    final protected function getPageBuilderService(): PageBuilderServiceInterface
    {
        if ($this->pageBuilderService === null) {
            /** @var PageBuilderServiceInterface */
            $pageBuilderService = $this->instanceManager->getInstance(PageBuilderServiceInterface::class);
            $this->pageBuilderService = $pageBuilderService;
        }
        return $this->pageBuilderService;
    }
    
    /**
     * Return the IDs of the Page Builders installed on
     * the site. The ID must not contain spaces or "-".
     * 
     * @return string[]
     */
    public function getInstalledPageBuilders(): array
    {
        return array_keys($this->getPageBuilderService()->getProviderNameServices());
    }
}
