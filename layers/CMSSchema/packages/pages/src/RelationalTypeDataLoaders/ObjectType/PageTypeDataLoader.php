<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;

class PageTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->getPageTypeAPI()->getPages($query, $options);
    }
}
