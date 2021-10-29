<?php

declare(strict_types=1);

namespace PoPSchema\Pages\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PageTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->getPageTypeAPI()->getPages($query, $options);
    }
}
