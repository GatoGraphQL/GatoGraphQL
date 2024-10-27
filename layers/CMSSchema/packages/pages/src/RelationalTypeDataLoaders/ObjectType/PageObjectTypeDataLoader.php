<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostObjectTypeDataLoader;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;

class PageObjectTypeDataLoader extends AbstractCustomPostObjectTypeDataLoader
{
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        if ($this->pageTypeAPI === null) {
            /** @var PageTypeAPIInterface */
            $pageTypeAPI = $this->instanceManager->getInstance(PageTypeAPIInterface::class);
            $this->pageTypeAPI = $pageTypeAPI;
        }
        return $this->pageTypeAPI;
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return $this->getPageTypeAPI()->getPages($query, $options);
    }
}
