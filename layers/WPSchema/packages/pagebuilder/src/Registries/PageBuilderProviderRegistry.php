<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Registries;

use PoPWPSchema\PageBuilder\PageBuilderProviders\PageBuilderProviderInterface;

class PageBuilderProviderRegistry implements PageBuilderProviderRegistryInterface
{
    /**
     * @var PageBuilderProviderInterface[]
     */
    protected array $pageBuilderProviders = [];

    public function addPageBuilderProvider(PageBuilderProviderInterface $pageBuilderFieldDirectiveResolver): void
    {
        $this->pageBuilderProviders[] = $pageBuilderFieldDirectiveResolver;
    }
    /**
     * @return PageBuilderProviderInterface[]
     */
    public function getPageBuilderProviders(): array
    {
        return $this->pageBuilderProviders;
    }
}
