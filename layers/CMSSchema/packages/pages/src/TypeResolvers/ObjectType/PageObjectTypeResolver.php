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

    final public function setPageObjectTypeDataLoader(PageObjectTypeDataLoader $pageObjectTypeDataLoader): void
    {
        $this->pageObjectTypeDataLoader = $pageObjectTypeDataLoader;
    }
    final protected function getPageObjectTypeDataLoader(): PageObjectTypeDataLoader
    {
        /** @var PageObjectTypeDataLoader */
        return $this->pageObjectTypeDataLoader ??= $this->instanceManager->getInstance(PageObjectTypeDataLoader::class);
    }
    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        /** @var PageTypeAPIInterface */
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
