<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType\PageObjectTypeDataLoader;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;

class PageObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?PageObjectTypeDataLoader $pageObjectTypeDataLoader = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final protected function getPageObjectTypeDataLoader(): PageObjectTypeDataLoader
    {
        if ($this->pageObjectTypeDataLoader === null) {
            /** @var PageObjectTypeDataLoader */
            $pageObjectTypeDataLoader = $this->instanceManager->getInstance(PageObjectTypeDataLoader::class);
            $this->pageObjectTypeDataLoader = $pageObjectTypeDataLoader;
        }
        return $this->pageObjectTypeDataLoader;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        if ($this->pageTypeAPI === null) {
            /** @var PageTypeAPIInterface */
            $pageTypeAPI = $this->instanceManager->getInstance(PageTypeAPIInterface::class);
            $this->pageTypeAPI = $pageTypeAPI;
        }
        return $this->pageTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'Page';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a page', 'pages');
    }

    public function getID(object $object): string|int|null
    {
        $page = $object;
        return $this->getPageTypeAPI()->getPageID($page);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageObjectTypeDataLoader();
    }
}
