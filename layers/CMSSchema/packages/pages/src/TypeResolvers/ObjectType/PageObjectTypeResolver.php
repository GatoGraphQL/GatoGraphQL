<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType\PageTypeDataLoader;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;

class PageObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?PageTypeDataLoader $pageTypeDataLoader = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final public function setPageTypeDataLoader(PageTypeDataLoader $pageTypeDataLoader): void
    {
        $this->pageTypeDataLoader = $pageTypeDataLoader;
    }
    final protected function getPageTypeDataLoader(): PageTypeDataLoader
    {
        return $this->pageTypeDataLoader ??= $this->instanceManager->getInstance(PageTypeDataLoader::class);
    }
    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }

    public function getTypeName(): string
    {
        return 'Page';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a page', 'pages');
    }

    public function getID(object $object): string | int | null
    {
        $page = $object;
        return $this->getPageTypeAPI()->getPageId($page);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPageTypeDataLoader();
    }
}
