<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Pages\RelationalTypeDataLoaders\ObjectType\PageTypeDataLoader;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PageObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?PageTypeDataLoader $pageTypeDataLoader = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    public function setPageTypeDataLoader(PageTypeDataLoader $pageTypeDataLoader): void
    {
        $this->pageTypeDataLoader = $pageTypeDataLoader;
    }
    protected function getPageTypeDataLoader(): PageTypeDataLoader
    {
        return $this->pageTypeDataLoader ??= $this->instanceManager->getInstance(PageTypeDataLoader::class);
    }
    public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }

    //#[Required]
    final public function autowirePageObjectTypeResolver(
        PageTypeDataLoader $pageTypeDataLoader,
        PageTypeAPIInterface $pageTypeAPI,
    ): void {
        $this->pageTypeDataLoader = $pageTypeDataLoader;
        $this->pageTypeAPI = $pageTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'Page';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a page', 'pages');
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
