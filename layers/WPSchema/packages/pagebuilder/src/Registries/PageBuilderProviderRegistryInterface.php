<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Registries;

use PoPWPSchema\PageBuilder\PageBuilderProviders\PageBuilderProviderInterface;

interface PageBuilderProviderRegistryInterface
{
    public function addPageBuilderProvider(PageBuilderProviderInterface $pageBuilderFieldDirectiveResolver): void;
    /**
     * @return PageBuilderProviderInterface[]
     */
    public function getPageBuilderProviders(): array;
}
